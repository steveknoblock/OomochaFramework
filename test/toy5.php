<?php


/*

IMPORTANT! 

PHP doesn't allow us to assign a reference to $this

If you implement a class with no member variables, calling the constructor does NOT produce an object. This can be a source of (great) consternation when producing classes which need no data storage.

Essentially if your class has no data members, all its methods are ONLY available statically, this can be a pain if defining object hierarchies with abstract base classes...

According to: http://us2.php.net/oop

*/

/*
Every method begins with

my ( $self, $c, @args ) = @_

or

 my ( $self, $c ) = @_;
 
 and references context like this
 
   $c->model('Database::Foo')->search( { country => $args[0] } );
        if ( $c->req->params->{bar} ) { # access GET or POST parameters
            $c->forward( 'bar' ); # process another action
            # do something else after forward returns            
        }

*/

class Context {
	var $controller;
	var $action;
	var $plugins;
	
	function Context() {
		print "<p>Entering Context Constructor</p>";
	
	$this->action = 'foobar';
	
		$this->controller &= new Controller( $this );
		
		$this->plugins->form &= new Form( $this );
	

	
		print "<p>Context Object:</p>";
		print "<pre>";
		print_r(get_object_vars($this));
		print "</pre>";
		print "<p>Form Object:</p>";
		print "<pre>";
		print_r(get_object_vars($this->plugins->form));
		print "</pre>";

		print "<p>Leaving Context Constructor</p>";
	}

}


class Controller {
	var $c;

	function Controller( &$c ) {
		print "<p>". ( empty( $c ) ? 'empty' : 'non empty') . "</p>";
		$this->c = $c;
		print "<p>". ( empty( $this->c ) ? 'empty' : 'non empty') . "</p>";
	}
	
	function foo() {
	
	print "<p>Entering controller->foo</p>";

	print "<p>Action: {$this->c->action}</p>";
	
	//print "<p>". ( empty( $this->c ) ? 'empty' : 'non empty') . "</p>";
	
	//print "<p>Context Object:</p>";
	//print_r(get_object_vars($this->c));
	//print "<p>Form Object:</p>";
	//print_r(get_object_vars($this->c->plugins->form));
	
	//print "C: " . $this->c->plugins->form->be();
	
	// I want to be able to write $c->t or $c->action here!
	print "Invoking Form";
	$this->c->plugins->form->be();

	}

}

class Form {
var $c;

	function Form( &$c ) {
		print "<p>". ( empty( $c ) ? 'empty' : 'non empty') . "</p>";
		$this->c = $c;
		print "<p>". ( empty( $this->c ) ? 'empty' : 'non empty') . "</p>";
	}
	
	function be() {
	print "Form";

		print "<p>". ( empty( $this->c ) ? 'empty' : 'non empty') . "</p>";
	
	
	// I want to be able to write $c->t or $c->action here!
	
	
	}

}

$c = new Context( );
$c->controller->foo();


?>