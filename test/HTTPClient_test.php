<?php
require_once "../src/HTTPClient.php";

class HTTPClient_test extends PHPUnit_Framework_TestCase{

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(){
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(){
    }

    public function test__case01() {
		$http = new HTTPClient();
		$param = $http->extractURL("http://windows.github.com/");
		$this->assertEquals($param["port"], 80);
		$this->assertEquals($param["host"], "windows.github.com");
		$this->assertEquals($param["path"], "/");
		$this->assertFalse(isset($param["query"]));
	}
	
    public function test__case02() {
		$http = new HTTPClient();
		$param = $http->extractURL("http://windows.github.com:8080/");
		$this->assertEquals($param["port"], 8080);
		$this->assertEquals($param["host"], "windows.github.com");
		$this->assertEquals($param["path"], "/");
		$this->assertFalse(isset($param["query"]));
	}
    public function test__case03() {
		$http = new HTTPClient();
		$param = $http->extractURL("http://windows.github.com/index.php?a=b");
		$this->assertEquals($param["path"], "/index.php");
		$this->assertTrue(isset($param["query"]));
		$this->assertEquals($param["query"]["a"], "b");
	}
}