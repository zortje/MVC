<?php
declare(strict_types = 1);

namespace Zortje\MVC\Model\Table\Entity\Exception;

use Zortje\MVC\Common\Exception\Exception;

/**
 * Class InvalidEntityPropertyException
 *
 * @package Zortje\MVC\Model\Table\Entity\Exception
 */
class InvalidEntityPropertyException extends Exception
{

    /**
     * {@inheritdoc}
     */
    protected $template = 'Entity %s does not have a property named %s';
}
