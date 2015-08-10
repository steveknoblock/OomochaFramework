<?php

class FC {
	var $foo;
	
function start() {
	//print "Requiring file";
	require_once 'toy11a.php';
	
	print_r( $list );
	
	
	//$f = new Fubar;
	//print "File required";
	//print "list: ". $list;
	//$f1 = new Fubar;
	//$f1->bar = "foo";
	//print $f1->bar;
	}
}

$fc = new FC;

$ctrl = new Controller;

print $fc->start();

?>