<?php

/*

What I want is an object that contains references to the class instances of the classes used in the application.

Why can't I say

$c->other->b->foo()

?

Wait a minute, this is all wrong.

If I have several plugins like 'form' and 'urlhelper' and 'textfilter' I can't refer to them all at once in a single scalar property.

$c is the object
$c->plugins is the 'plugins' property

$c->plugins['form']
$c->plugins['urlhelper']
$c->plugins['textfilter']

is the only way I can see to reference multiple objects from this property.

Yet, you can say things like this $this->model->getLinks() or $c->request->params

How?

Well, if you say

class Request {
	var $params;
	
	function Request() {
		$this->params = array( 'a'=> 'aoo', 'b'=> 'boo');
	}
}

class Context {
	var $request;

	function Context() {
		$this->request = new Request;		
	}
}

$c = new Context;
print_r( $c->request->params );

$c->request->params
    ^        ^ is the name of a property in the Request object
	|---- is the _name_ of a property referencing a Request object, it _could_ be called anything.
	
I think that to be able to say

$c->plugins->ANYPLUGIN

you would need polymorphism or something. Otherwise, $c->plugins must be an array of references to objects. Yikes.

Otherwise, a property would be needed for each plugin.

$c->formplugin
$c->urlhelperplugin

etc.

How would you do a DOM?

$document->html->p->em

$document->html->blockquote->em

html must hold more than one, yet you can reference these in Javascript doc.html.p.em

or something like that.

I suppose it requires multiple inheritence.

$c->plugins->form
$c->plugins->urlhelper

instead of

$c->plugins[form] // obj ref
$c->plugins[urlhelper] // obj ref

$c->plugins[form]->fields

is ugly.

So I can go infinitely deep in references to objects ultimately to a property.

$object->object->object->property

and I can invoke a method deeply

$object->object->object->method

but I can't say

$object->containerobject->oneofseveralobjectsinalist->method

I could say

$object->containerobject[oneofseveralobjectsinalist]->method

or perhaps

$object->containerobject(oneofseveralobjectsinalist)->method

where container object returns the correct reference.

$object->containerobject()->method

if there wre a way for the container method to determine the correct object from the class of the call.

So

$c->plugins->form->fields()

would refer to the plugins method of the plugins container object and automatically know that form is the form object and fields is its method.

'plugins' must automagically represent the correct object.

$c->plugins->foo->fields()
$c->plugins->bar->other()

As long as plugins is a property of c I do not see anyway to do this.

What about extended? Does that gain anything?

Context::Plugins::Form::method()

???


Application::getInstance()->user->FileManager['base']

shows you can in php5, mix () and ->

I believe what I need is in

http://us2.php.net/manual/en/language.oop5.overloading.php

where

$c->plugins-foo->fields()

would use this dyanmic capability to determine to call fields() on 'foo' instance.

*/

class A {

// access value from instance of C here

}

class B {
	var $blah;

// access value from instance of C here

	function foo() {
		print "Foo!";
	}

}

class C {
	var $foo;
	var $other;

	// consructor
	function C() {
	
	
	$b &= new B;
	$this->other;
	
	$this->other->b->foo();
	
	}
}


//$c &= new C;

/**

class Request {
	var $params;
	
	function Request() {
		$this->params = array( 'a'=> 'aoo', 'b'=> 'boo');
	}
}

class Context {
	var $request;

	function Context() {
		$this->request = new Request;		
	}
}

$c = new Context;
print_r( $c->request->params );

*/


class Request {
	var $params;
	
	function Request() {
		$this->params = array( 'a'=> 'aoo', 'b'=> 'boo');
	}
}

class Form {
	var $fields;
	
	function Form() {
		$this->fields = array( 'title'=> 'Title', 'describe'=> 'A title.');
	}
	
	function getFields() {
		return $this->fields;
	}
}

class Context {
	var $request;
	var $plugins;
	
	function Context() {
		$this->request = new Request;
		$this->plugins[form] = new Form;
	}
}

$c = new Context;
print_r( $c->request->params );

print_r( $c->plugins[form]->fields );

print_r( $c->plugins[form]->getFields() );


?>