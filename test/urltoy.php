<?php

// Toy to play with URL schemes.


/**
 * Source may be either a url parameter containing a path
 * specifying module, method and parameters or a path info
 * specifying the same values.
 * The source is always a string like /foo/bar/baz/qux
 */

 $source = $_REQUEST['action'];

 //http://www.phphelp.com/test/projects/sandbox/mvc/index.php?action=links/edit/&id=1
 // this is one with normal params and virtual url mix
 
/**
 * Scheme 1
 *
 
 This scheme splits up the url on / and the output is a single array
 containing the elements in sequential order. This is the mechanism
 used by Code Igniter, which passes this to the controller without
 saying how to use it.
 
 input: /foo/bar/bozo/clown/bread/crumb
 
 output: array ( 'foo', 'bar', 'bozo', 'clown', 'bread', 'crumb' )
    
	
	IMPORTANT: This code expects a leading slash:
	
	urltoy.php?action=/links/edit
	NOT
	urltoy.php?action=links/edit
	
	for example, when the request comes in as an 'action' parameter.
	
	This could be made intelligent by testing for a leading slash and
	stripping it using a regex. Or just stripping any leading slash before
	exploding/splitting.
 */
 
 
 /**
  * Handler A
  * Preseves original array of virtual url pieces.
  */

	$pieces = explode('/', $source);
	
	// siphon off module and method
	//we know by convention that the first two elements are the module and method, the rest
	// are parameters. The paramters start at the third element in the array.
	// The leading slash causes the index to start at one for the items of interest.
	
	$module = $pieces[1];
	$method = $pieces[2];
  	
	/**
	 * Retrieve virtual url parameters.
	 * The goal is to create an associative array of parameters and values:
	 * array(
	 *  'bozo' => 'clown',
	 *  'bread' => 'crumb'
	 * )
	 */
	 
	$params = array_slice($pieces, 3, count($pieces));
	
	print "<p>A</p>";
	print "<p>Module: $module</p>";
	print "<p>Method: $method</p>";
	print "<pre>";
	print_r( $params );
	print "</pre>";
	
	/**
	  Example
	 urltoy.php?action=/links/edit/foo/bar/baz/qux
	 Module: links

		Method: edit
		
		Array
		(
		  [0] => foo
		  [1] => bar
		  [2] => baz
		  [3] => qux
		)
	*/

	
 /**
  * Handler B
  * Preseves original array of virtual url pieces. Uses regex. Probably the fastest.
  */

	$pieces = preg_split('/\//', $source, -1, PREG_SPLIT_NO_EMPTY);

	//$pieces = explode('/', $source);
	
	// siphon off module and method
	//we know by convention that the first two elements are the module and method, the rest
	// are parameters. The paramters start at the third element in the array.
	// The leading slash causes does not cause a problem when preg_split is told not to retain empty elements. The count base returns to zero for the indexes.
	
	$module = $pieces[0];
	$method = $pieces[1];
  	
	/**
	 * Retrieve virtual url parameters.
	 * The goal is to create an associative array of parameters and values:
	 * array(
	 *  'bozo' => 'clown',
	 *  'bread' => 'crumb'
	 * )
	 */
	 
	$params = array_slice($pieces, 2, count($pieces));
	
	print "<p>B</p>";
	print "<p>Module: $module</p>";
	print "<p>Method: $method</p>";
	print "<pre>";
	print_r( $params );
	print "</pre>";
	
	/**
	  Example
	 urltoy.php?action=/links/edit/foo/bar/baz/qux
	 Module: links

		Method: edit
		
		Array
		(
		  [0] => foo
		  [1] => bar
		  [2] => baz
		  [3] => qux
		)
	*/
	
	
 /**
  * Handler C
  * Shifts method and module off array.
  */

	$pieces = explode('/', $source);
 
	array_shift( $pieces); // strip leading slash
	$module = array_shift( $pieces);
	$method = array_shift( $pieces);
	// I prefer to remove these from the array, although one could slice them all
	// off; we know by convention that the first two elements are the module and method, the rest
	// are parameters.
 	/**
	 * The goal is to create an associative array of parameters and values:
	 * array(
	 *  'bozo' => 'clown',
	 *  'bread' => 'crumb'
	 * )
	 */
	$params = array_slice($pieces, 0, count($pieces));
	
	print "<p>Module: $module</p>";
	print "<p>Method: $method</p>";
	print "<pre>";
	print_r( $params );
	print "</pre>";
 
 	/**
	  Example
	 urltoy.php?action=/links/edit/foo/bar/baz/qux
	 Module: links

		Method: edit
		
		Array
		(
		  [0] => foo
		  [1] => bar
		  [2] => baz
		  [3] => qux
		)
	*/
 
 /**
   Conclusion: This is the simplest method, preserves the "directory look" of virtual urls, no strange delimiters are ncessary. Works with both 'action' parameter style urls and path_info urls. Requires less processing, given the module and method are just elements in the same array as parameters and a quick array slice will give you the parameters. Since the delimiter is already url-special, I think you need not worry about it appearing in a parameter value, since that character should be url-encoded (escaped) anyway.

Magic quotes should be disabled for these to work.
  */
  
/**
 This scheme splits up the url on / and then parts on , which is the
 mechanism Joomla uses.
 
 input: /foo/bar/bozo,clown/bread,crumb
 
 output: array ( 'foo', 'bar', array( 'bozo' => 'clown', 'bread' => 'crumb') )
  (or something like it)
 */
 
 
 /*
 
 By the way, you do not really need to define a list of actions. All you need to do is get the first
 two elements from the url scheme, use the first one to check for the existience of a module file by that name, if it it exists, include it; and check the second one to see if a function by that name exists in the module, then if both check out, execute it. No need to ever make a list. If a requested action is not found, throw an error.
 
 */
 
?>