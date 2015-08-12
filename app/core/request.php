<?php

/**
 * Request
 * Abstract the HTTP request and parsing of parameters.
 */

/*

METHOD path/to/resource HTTP 1.1

GET path/to/resource HTTP 1.1
User-Agent: foo

/module/method/param1/value1/param2/value2/param3/value3/

ltrim /

split on /

[
module,method,param1,value1

]

or

ltrim /
rtrim /

split on /

resulting array


module/method/param1/value1/param2/value2/param3/value3







*/

class Request {

	protected $request;
	
	/*
	protected $get;
	protected $post;
	*/

	protected $protocol;
	protected $path;
	protected $method;
	protected $params;

	
	function __construct()
	{

		$this->_init();

	}

	private function _init() {

		//$this->method = $_SERVER['REQUEST_METHOD'];
		//$this->path = $_SERVER['PATH_INFO'];
		//$this->params = $_REQUEST;

		$this->path = 'module/method/key1/value1/key2/value2/key3/value3';

		// etc. as needed
		//$this->files $_FILES
		//$this->session $_SESSION
		//$this->server $_SERVER

	}

/**
 * framework URL structure
 * /module/method
 * /module/method/param1/value1/param2/value2/param3/value3/
 */


	public function parseRequest() {

 		preg_match_all("/([^\/]+)\/([^\/]+)/", $this->path, $p);
 
 		$this->module = array_shift($p[1]);
		$this->method = array_shift($p[2]);

		$this->params = array_combine($p[1], $p[2]);

		$this->route = $this->module . DIRECTORY_SEPARATOR . $this->method;
	}


	public function getParams() {
		return $this->params;
	} 

}

$r = new Request();
$r->parseRequest();
var_dump($r);