<?php

/**********************************************************************
 *                          SweetPear Framework
 *                       Copyright 2006 Steve Knoblock
 *                                GPL License
 **********************************************************************/
  
/**
 * Application Context
 * Provides context object for application and request flow. Lots of goodies!
 * @author Steve Knoblock
 * @version 0
 * @date begin: 
 * @date revised: 
 */
 

class Context {
 
var $actions;		// array maybe it would be easier if each action were a object, then you could reference them by action.class and action.method props
var $current_class;
var $current_method;
var $request;		// object
var $config;		// application configuration, can be a reference to class config object
var $plugins;		// array of helper objects for the application to use
// FIXME: we need PHP5 for this, but it should be able to make it automatically find the correct object when saying something like $c->plugins->form instead of $c->plugins[form]
var $auth_req;


/*

all these may be useful as context or request properties
<br>
[REQUEST_METHOD] => GET
                    [QUERY_STRING] => action=links/list
                    [REQUEST_URI] => /test/projects/sandbox/mvc/index.php?action=links/list
                    [SCRIPT_NAME] => /test/projects/sandbox/mvc/index.php
                    [PATH_TRANSLATED] => /usr/www/users/cityg/phphelp.com/test/projects/sandbox/mvc/index.php
                    [PHP_SELF] => /test/projects/sandbox/mvc/index.php
                )

				also, just making $_SERVER available through the framework would be good.

*/

 	/**
	 * Constructor
	 *
	 */
	function Context() {
	
	$this->request = new Request;
	
	}
 
			
		function add_plugin( $class, $method ) {
			$this->actions[$class][] = array( $pattern => $method );
		}
		
	}
?>