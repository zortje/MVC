<?php
declare(strict_types = 1);

namespace Zortje\MVC\Controller;

use Zortje\MVC\Controller\Exception\ControllerInvalidSuperclassException;
use Zortje\MVC\Controller\Exception\ControllerNonexistentException;
use Zortje\MVC\User\User;

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
     * @var string App file path
     */
    protected $appPath;

    /**
     * @var null|User User
     */
    protected $user;

    /**
     * @param \PDO      $pdo
     * @param string    $appPath
     * @param null|User $user
     */
    public function __construct(\PDO $pdo, string $appPath, User $user = null)
    {
        $this->pdo     = $pdo;
        $this->appPath = $appPath;
        $this->user    = $user;
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
        $controller = new $controller($this->pdo, $this->appPath, $this->user);

        return $controller;
    }
}
