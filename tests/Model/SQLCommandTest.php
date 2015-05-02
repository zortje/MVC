<?php

namespace Zortje\MVC\Tests\Model;

use Zortje\MVC\Model\SQLCommand;
use Zortje\MVC\Tests\Model\Fixture\CarEntity;
use Zortje\MVC\Tests\Model\Fixture\CarTable;

/**
 * Class SQLCommandTest
 *
 * @package            Zortje\MVC\Tests\Model
 *
 * @coversDefaultClass Zortje\MVC\Model\SQLCommand
 */
class SQLCommandTest extends \PHPUnit_Framework_TestCase {

	private $pdo;

	public function setUp() {
		$this->pdo = new \PDO("mysql:host=127.0.0.1;dbname=myapp_test", 'root', '');
	}

	/**
	 * @covers ::insertInto
	 * @covers ::__construct
	 */
	public function testInsertInto() {
		$table = new CarTable($this->pdo);

		$sqlCommand = new SQLCommand($table->getTableName(), CarEntity::getColumns());

		$expected = 'INSERT INTO `cars` (`id`, `make`, `model`, `hp`, `modified`, `created`) VALUES (NULL, :make, :model, :hp, :modified, :created);';

		$this->assertSame($expected, $sqlCommand->insertInto());
	}

}
