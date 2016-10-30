<?php
declare(strict_types = 1);

namespace Zortje\MVC\Model\Table\Entity\Exception;

use Zortje\MVC\Common\Exception\Exception;

/**
 * Class InvalidValueTypeForEntityPropertyException
 *
 * @package Zortje\MVC\Model\Table\Entity\Exception
 */
class InvalidValueTypeForEntityPropertyException extends Exception
{

    /**
     * {@inheritdoc}
     */
    protected $template = 'Entity property expected value type to be "%s", got "%s" instead';
}
