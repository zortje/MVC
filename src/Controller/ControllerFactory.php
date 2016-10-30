<?php
declare(strict_types = 1);

namespace Zortje\MVC\Controller;

use Zortje\MVC\Configuration\Configuration;
use Zortje\MVC\Controller\Exception\ControllerInvalidSuperclassException;
use Zortje\MVC\Controller\Exception\ControllerNonexistentException;
use Zortje\MVC\Model\Table\Entity\Entity;
use Zortje\MVC\Network\Request;

/**
 * Class ControllerFactory
 *
 * @package Zortje\MVC\Controller
 */
class ControllerFactory
{

    /**
     * @var \PDO PDO
     */
    protected $pdo;

    /**
     * @var Configuration
     */
    protected $configuration;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Entity|null User
     */
    protected $user;

    /**
     * ControllerFactory constructor.
     *
     * @param \PDO          $pdo
     * @param Configuration $configuration
     * @param Request       $request
     * @param Entity|null   $user
     */
    public function __construct(\PDO $pdo, Configuration $configuration, Request $request, Entity $user = null)
    {
        $this->pdo           = $pdo;
        $this->configuration = $configuration;
        $this->request       = $request;
        $this->user          = $user;
    }

    /**
     * Initialize controller
     *
     * @param string $controller Controller class name
     *
     * @return Controller Controller object
     *
     * @throws ControllerInvalidSuperclassException
     * @throws ControllerNonexistentException
     */
    public function create(string $controller): Controller
    {
        if (!class_exists($controller)) {
            throw new ControllerNonexistentException([$controller]);
        } elseif (!is_subclass_of($controller, Controller::class)) {
            throw new ControllerInvalidSuperclassException([$controller]);
        }

        /**
         * @var Controller $controller
         */
        $controller = new $controller($this->pdo, $this->configuration, $this->request, $this->user);

        return $controller;
    }
}
