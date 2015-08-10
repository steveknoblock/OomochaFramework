<?php

// example of getting access to context in another object

class Request {
	var $params;
	
	function Request() {
		$this->params = array( 'a'=> 'aoo', 'b'=> 'boo');
	}
}

class Links {
	var $data;
	var $c;
	
	function Links( $c ) {
		$this->c = $c;
		$this->data = array( 'title'=> 'Title', 'describe'=> 'A title.');
	}
	
	function getData() {
		return $this->data;
	}
	
	function dosomething() {
		// access request params from here
		$c=$this->c;
		
		print "<pre>";
		print_r( $c->request->params );
		print "</pre>";
		
	}
}

class Context {
	var $request;
	var $plugins;
	
	function Context() {
		$this->request = new Request;
	}
}

$c = new Context;

$l = new Links( $c );
$l->dosomething();

//print_r( $c->request->params );



?>