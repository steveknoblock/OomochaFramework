<?php


class Context {
	var $controller;
	var $t;
	var $action;
	
	function Context( $text ) {
		$this->t = $text;
		
		$this->controller = new Controller( $this );
	}

}


class Controller {

	function Controller( $c ) {
	
		print ( empty( $c ) ? 'empty' : 'non empty');
	$this->c = $c;
		
	}
	
	function foo() {
	print "Foo!";

		print ( empty( $this->c ) ? 'empty' : 'non empty');
	
	
	// I want to be able to write $c->t or $c->action here!
	
	
	}

}

$c = new Context( 'foo' );

/* instead of
$c->controller = new Controller();

*/
$c->controller->foo();

?>