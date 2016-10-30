<?php
declare(strict_types = 1);

namespace Zortje\MVC\Tests\Model\Table\Exception;

use Zortje\MVC\Model\Table\Exception\TableNameNotDefinedException;

/**
 * Class TableNameNotDefinedExceptionTest
 *
 * @package            Zortje\MVC\Tests\Model\Table\Exception
 *
 * @coversDefaultClass Zortje\MVC\Model\Table\Exception\TableNameNotDefinedException
 */
class TableNameNotDefinedExceptionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers ::__construct
     */
    public function testMessage()
    {
        $this->expectException(TableNameNotDefinedException::class);
        $this->expectExceptionMessage('foo');
        
        throw new TableNameNotDefinedException('foo');
    }

    /**
     * @covers ::__construct
     */
    public function testMessageArray()
    {
        $this->expectException(TableNameNotDefinedException::class);
        $this->expectExceptionMessage('Subclass Foo does not have a table name defined');
        
        throw new TableNameNotDefinedException(['Foo']);
    }
}
