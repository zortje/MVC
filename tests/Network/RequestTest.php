<?php

namespace Zortje\MVC\Tests\Network;

use Zortje\MVC\Network\Request;

/**
 * Class RequestTest
 *
 * @package            Zortje\MVC\Tests\Network
 *
 * @coversDefaultClass Zortje\MVC\Network\Request
 */
class RequestTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @covers ::getPath
	 */
	public function testGetPath() {
		$request = new Request('https://www.example.com/cars', []);
		$this->assertEquals('/cars', $request->getPath());

		$request = new Request('https://www.example.com/cars/ford', []);
		$this->assertEquals('/cars/ford', $request->getPath());
	}

	/**
	 * @covers ::getPath
	 */
	public function testGetPathEmptyPath() {
		$request = new Request('https://www.example.com', []);
		$this->assertEquals('', $request->getPath());
	}

}