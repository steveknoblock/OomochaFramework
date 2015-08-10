<?php


class Context {

var $t;

function Context( $text ) {
	$this->t = $text;
}

}


class Controller Extends Context {

function Controller() {
}

function baz() {
	
	print "C:";
	print $this->t;
}

}

$c = new Context();


?>