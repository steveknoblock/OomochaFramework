<?php

function log_err( $file, $line, $type, $message )
{

$event_types = array(
	0=> 'Information',
	1=> 'Error',
	2=> 'Warning'
);
		print "<h3>Application Framework Event:</h3>";
		print "<p>Type: {$event_types[$type]}</p>";
		print "<p>Message: $message</p>";
		print "<p>Location: $file @ $line</p>";
}

log_err( __FILE__, ___LINE___, 2, "An error has occured that prevents the framework from responding to login events.");

/*

___LINE___	 The current line number of the file.
___FILE___	The full path and filename of the file. If used inside an include, the name of the included file is returned. Since PHP 4.0.2, ___FILE___ always contains an absolute path whereas in older versions it contained relative path under some circumstances.
__FUNCTION__	The function name. (Added in PHP 4.3.0) As of PHP 5 this constant returns the function name as it was declared (case-sensitive). In PHP 4 its value is always lowercased.
__CLASS__	The class name. (Added in PHP 4.3.0) As of PHP 5 this constant returns the class name as it was declared (case-sensitive). In PHP 4 its value is always lowercased.
__METHOD__

By the way, if __FILE__ is within a function call, its value corresponds to the file it was defined in and not the file that it was called from.  Also, I used $script and strtolower instead of realpath because if the script is deleted after inclusion but before realpath is called (which could happen if the test is deferred), then realpath would return empty since it requires an extant file or directory.


in reply to x123 at bestof dash inter:
I believe, this is not a bug, but a feature.
__FILE__ returns the name of the include file, while $PHP_SELF returns the relative name of the main file.
It is then easy to get the file name only with substr(strrchr($PHP_SELF,'/'),1)

DIRECTORY_SEPARATOR constant which you can use to make you scripts more portatable between OS's with different directory structures.
*/

?>