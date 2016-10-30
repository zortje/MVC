<?php
declare(strict_types = 1);

namespace Zortje\MVC\Controller\Exception;

use Zortje\MVC\Common\Exception\Exception;

/**
 * Class ControllerActionProtectedInsufficientAuthenticationException
 *
 * @package Zortje\MVC\Controller\Exception
 */
class ControllerActionProtectedInsufficientAuthenticationException extends Exception
{

    /**
     * {@inheritdoc}
     */
    protected $template = 'Controller %s protected action %s requires authentication';
}
