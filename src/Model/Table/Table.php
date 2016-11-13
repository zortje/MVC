<?php
declare(strict_types = 1);

namespace Zortje\MVC\Model\Table;

use Zortje\MVC\Model\SQLCommand;
use Zortje\MVC\Model\Table\Entity\Entity;
use Zortje\MVC\Model\Table\Entity\EntityFactory;
use Zortje\MVC\Model\Table\Entity\EntityProperty;
use Zortje\MVC\Model\Table\Entity\Exception\EntityClassInvalidSuperclassException;
use Zortje\MVC\Model\Table\Entity\Exception\EntityClassNonexistentException;
use Zortje\MVC\Model\Table\Entity\Exception\EntityClassNotDefinedException;
use Zortje\MVC\Model\Table\Entity\Exception\InvalidEntityPropertyException;
use Zortje\MVC\Model\Table\Exception\TableNameNotDefinedException;

/**
 * Class Table
 *
 * @package Zortje\MVC\Model\Table
 */
abstract class Table
{

    /**
     * @var \PDO Connection
     */
    protected $pdo;

    /**
     * @var string Table name
     */
    protected $tableName;

    /**
     * @var String Entity class
     */
    protected $entityClass;

    /**
     * @var SQLCommand SQL Command
     */
    protected $sqlCommand;

    /**
     * @param \PDO $pdo
     *
     * @throws TableNameNotDefinedException If table name is not defined in subclass
     * @throws EntityClassNotDefinedException If entity class is not defined in subclass
     * @throws EntityClassNonexistentException If entity class is nonexistent
     * @throws EntityClassInvalidSuperclassException If entity class is not extending base entity class
     */
    public function __construct(\PDO $pdo)
    {
        if ($this->tableName === null) {
            throw new TableNameNotDefinedException([get_class($this)]);
        }

        if ($this->entityClass === null) {
            throw new EntityClassNotDefinedException([get_class($this)]);
        } elseif (!class_exists($this->entityClass)) {
            throw new EntityClassNonexistentException([get_class($this), $this->entityClass]);
        } elseif (!is_subclass_of($this->entityClass, Entity::class)) {
            throw new EntityClassInvalidSuperclassException([$this->entityClass]);
        }

        $this->pdo = $pdo;

        $this->sqlCommand = $this->createCommand();
    }

    /**
     * Get table name
     *
     * @return string Table name
     */
    public function getTableName(): string
    {
        return $this->tableName;
    }

    /**
     * Find all entities
     *
     * @return Entity[] Entities
     */
    public function findAll(): array
    {
        $stmt = $this->pdo->prepare($this->sqlCommand->selectFrom());
        $stmt->execute();

        return $this->createEntitiesFromStatement($stmt);
    }

    /**
     * Find all entities for given conditions
     *
     * ```
     * [
     *     '{entity_property}' => '{property_value}'
     * ]
     * ```
     *
     * @param array $conditions
     *
     * @throws InvalidEntityPropertyException If entity does not have that property
     *
     * @return Entity[] Entities
     */
    public function findBy(array $conditions): array
    {
        /**
         * Check if entity have the properties in conditions
         *
         * @var Entity $entity
         */
        $reflector = new \ReflectionClass($this->entityClass);

        $entity = $reflector->newInstanceWithoutConstructor();

        foreach (array_keys($conditions) as $key) {
            if (!isset($entity::getColumns()[$key])) {
                throw new InvalidEntityPropertyException([$this->entityClass, $key]);
            }
        }

        /**
         * Validate values in conditions
         */
        foreach ($conditions as $key => $value) {
            $entityProperty = new EntityProperty($entity::getColumns()[$key]);
            $entityProperty->validateValue($value);
        }

        /**
         * Execute with key-value condition
         */
        $parameters = [];

        foreach ($conditions as $key => $value) {
            $parameters[":$key"] = $value;
        }

        $stmt = $this->pdo->prepare($this->sqlCommand->selectFromWhere(array_keys($conditions)));
        $stmt->execute($parameters);

        return $this->createEntitiesFromStatement($stmt);
    }

    /**
     * Insert entity into database
     *
     * @param Entity $entity Entity object
     *
     * @return Entity Inserted entity
     */
    public function insert(Entity $entity): Entity
    {
        $stmt = $this->pdo->prepare($this->sqlCommand->insertInto());

        $now = (new \DateTime())->format('Y-m-d H:i:s');

        $array = array_merge($entity->toArray(), [
            'modified' => $now,
            'created'  => $now
        ]);

        $stmt->execute($array);

        return $entity;
    }

    /**
     * Update entity in the database
     *
     * @param Entity $entity Entity object
     *
     * @return bool True if row was affected, otherwise false
     */
    public function update(Entity $entity): bool
    {
        if ($entity->isAltered()) {
            /**
             * Set modified to now
             */
            $entity->set('modified', new \DateTime());

            /**
             * Get altered columns for SQL command
             */
            $alteredColumns = array_keys($entity->getAlteredColumns());

            $stmt = $this->pdo->prepare($this->sqlCommand->updateSetWhere($alteredColumns));

            /**
             * Execute with altered array
             */
            $stmt->execute($entity->alteredToArray(true));

            // @todo return true if row is altered
        }

        return false;
    }

    public function delete(Entity $entity)
    {
        // @todo Implement
    }

    /**
     * Creates an array of Entity objects from statement
     *
     * @param \PDOStatement $statement
     *
     * @return Entity[] Entities from statement
     */
    protected function createEntitiesFromStatement(\PDOStatement $statement): array
    {
        $entities = [];

        $entityFactory = new EntityFactory($this->entityClass);

        foreach ($statement as $row) {
            $entities[] = $entityFactory->createFromArray($row);
        }

        return $entities;
    }

    /**
     * Create SQLCommand for this Table with provided Entity
     *
     * @return SQLCommand
     */
    protected function createCommand(): SQLCommand
    {
        $reflector = new \ReflectionClass($this->entityClass);

        $entity = $reflector->newInstanceWithoutConstructor();

        $columns = $entity::getColumns();

        return new SQLCommand($this->tableName, $columns);
    }
}
