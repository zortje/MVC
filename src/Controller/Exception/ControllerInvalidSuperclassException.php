<?php
declare(strict_types = 1);

namespace Zortje\MVC\Controller\Exception;

use Zortje\MVC\Common\Exception\Exception;

/**
 * Class ControllerInvalidSuperclassException
 *
 * @package Zortje\MVC\Controller\Exception
 */
class ControllerInvalidSuperclassException extends Exception
{

    /**
     * {@inheritdoc}
     */
    protected $template = 'Controller %s is not a subclass of Controller';
}
