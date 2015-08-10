<?

/**********************************************************************
 *                          SweetPear Framework
 *                       Copyright 2006 Steve Knoblock
 *                                GPL License
 **********************************************************************/
  
/**
 * MVC Controller
 * Controller functionality.
 * @author Steve Knoblock
 * @version 0
 * @date begin: 
 * @date revised: 
 */
 
// this is where the context could be passed to the controller, it would hold the pointers to model, view and even controller
// $c->model->* $c->view->* $c->controller->*

class MVC_Controller {
    var $model;
    var $action;
    var $view;
	var $c;
	
    function MVC_Controller( $c ) { //$context
        $this->view = new MVC_View();
		
		$this->action = $c->action->method;
		
		//print "<p>(Controller Constructor) Context Action: {$c->action->method}</p>";
				
		//print "<p>(Controller Constructor) Action: $this->action</p>";
		
		
		/*
		The problem is only the controller constructor has access to the context. It can
		find the action to take, but it is not making the context available in the methods
		or methods of the extended controller instance. The solution at this time is to
		give the controller a property for storing the reference to the context.
		
		*/

		// Debugging	
		//if( isset($c) ) {
		// 	print "Controller has the Context!";
		//	print "<pre>";
		//	print_r( $c );
		//	print "</pre>";
		//}
		
		$this->c = $c;
		
    }

	// note: method no longer needed, see below
	// I do not see where this method is ever called with a method name or params
    function doAction($method = "defaultMethod", $params = array() ) {
      
	
	
	$method = $this->action;
   //print "<p>(MVC_Controller->doAction) Method: $method</p>";
    
    
       // print "Action: $method<br>";
        if (empty($method)) {
            $this->defaultMethod($params);
        } else if (method_exists($this, $method)) {
            call_user_func(array(&$this, $method), $params);
        } else {
            $this->nonexisting_method($method);
        }
    }

    function defaultMethod() {
        print "This is a default action. <br>";
    }

    function nonexisting_method($method) {
        print "Sorry, that method '$method' does not exist.<br>";
    }
	
	
	// invoke a method in this class by redirecting using its corresponding action
	function forward( $method ) {
	
	//	'links/list', 'links', 'displayLinks'
	// url action method name or actual function name?
	
	// this is more of a plain redirection right now, just a wrapper around a redirection, but a true forward would be able to invoke an action internally or externally through a redirect
/**

What would be helpful is if this function could know the name of the class, so it would already know the module name.

get_class()

This could also possibly invoke the method directly, perhaps we need two functions, one to redirect and one to invoke directly using call_user_func, etc.
*/	


	// be nice to do this
	// print out actions to see what the array looks like
	//$this->c->actions[displayLinks]

	// I thought the manual said you do not need to specify an object when
	// invoking a method? Is it because of the superclass?	
	//print "This class is". get_class($this);

	// strip standard 'controller' from class name
	// to get module name
	$module = preg_replace('/controller/', '', get_class($this));
	
		$location = "index.php?action=$module/$method";
		header("Location: $location");
	}
	
	
	// helpers
	// is this the best place in the framework to define these?
	function MakeLink( $module, $method, $params ) {
	
	$link = '/';
	$link .= 'index.php?';
	$link .= 'action=';
	$link .= "$module/$method";
	foreach( $params as $n=>$v ) {
		$link .= "/&amp;$n=$v";
	}
	//http://www.phphelp.com/test/projects/sandbox/mvc/index.php?action=links/edit/&id=1
	
	}
	
	// based on current action
	function MakeLinkFromAction( $module, $method, $params ) {
	// assume last action?
	// like if you invoked links/browse
	}
	
}

?>