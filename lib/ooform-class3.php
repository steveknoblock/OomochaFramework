<?php

/**********************************************************************
 *			                 ooForm
 *						Easy forms handling.
 * Version: 1.1.1
 * Developer: Steve Knoblock
 * Begin: Wednesday, November 03, 2004
 * Revised: Sunday, April 03, 2005
 * Credits: Steve Knoblock. This project would not have been possible
 * without the generous help of Nate Wiger.
 **********************************************************************/

 // MODIFIED FOR FRAMEWORK
 // DO NOT USE INDEPENDENTLY
 
 /*
  * Dependencies
  *
  */
  
define('OOFORMBASE','/usr/www/users/cityg/phphelp.com/test/projects/sandbox/mvc/lib');
 
require_once OOFORMBASE . '/ooform-template-class.php';
require_once OOFORMBASE . '/ooform-messages-class.php';

define('DEBUG_VERBOSE', 1);

 /*
  * Class Definition
  *
  */
  
class ooForm {

/******************************************************************
 *                        Class Member Vars
 *
 */
 
/*
 * Predefined validation rules
 *
 */

var $fields; // assoc. array storing meta data and state for each field

var $fields_list; // array listing field names

/* Note: Remember, PHP's $_POST or $_GET are superglobals available within the class without passing them.
 */

var $params_list; // parameter array

var $rules_list = array(
	'_mysqldate' => '#\d\d\d\d/\d\d\/\d\d#',
    '_name' => '/^[a-zA-Z]+$/',
    '_email' => '/^.+\@([a-z0-9]+(-[a-z0-9]+)?\.)+([a-z]{2,3})$/',
    '_zip' => '/^\d{5}$|^\d{5}\-\d{4}$/',
    '_date' => '#\d\d/\d\d/\d\d\d\d#'
);

var $validate_list; // assoc. array matching fields to validation rules

var $required_list = array(); // array naming required fields

var $messages_list; // array of messages for each field


var $labels_list; // array of messages for each field

// housekeeping

var $error; // error message

var $debug = 0;

var $sticky = 1;

//var $oomessages;

/*

Usage:

if ( $form->submitted
     && $form->validate ) {
	 // response
	 } {
	 // render
	 }
	 
*/

// can't have var and function with same name?
//var $submitted; // future use

var $fail_validate;
var $fail_require;

var $templateobj;

/******************************************************************
 *                        Class Member Functions/Methods
 *
 */
 

 /*
  * Constructor
  *
  */
 
 function ooForm( ) {

 $this->templateobj = new ooFormTemplate(); // $this->templateobj = new ooFormTemplate('templates'); this has to be changed to pass generic options to template engine as required by the engine, each is different, you can't put 'template' in here, you've got to pass an array of options meaningful to the engine.

$this->params_list = $_REQUEST;

//if( $this->debug ) {
//	print "<pre>";
//	print "Dumping Constructor\n";
//	print "Field List\n";
//	print_r( $this->fields_list );
//	print "CGI Parameters\n";
//	print_r( $this->params_list );
//  	print "</pre>";
//}

 } // end constructor

function init( $field_list ) {

  
  	//if( $this->debug ) {
	//	print "<pre>";
	//	print "Init Field List<br>";
	//	print_r( $field_list );
	//  	print "</pre>";
	//}
	
	/**
	 * Becuase the form object is instantiated by the front controller
	 * once and used for all forms, the init() can overwrite an existing
	 * instance. Only invoke if fields are empty.
	 */
	// Muhahahaha!
	if( empty( $this->fields_list )) {
	$this->fields_list = $field_list;
	  
	foreach ( $this->fields_list as $field_name ) {
		$this->fields[$field_name] = array(
			'name'		=> $field_name,
			'value'		=> '',
			'invalid'	=> 0,
			'is_required' => 0, // change name to 'required' ?
			'options'	=> array(), // this stores options for menu fields
			'label'		=> '', // default is empty for a reason see below.
			'error'		=> ''
		);
	 }
	}
	 // the default label field is empty so that when the placeholder is empty the message reads: please enter a value for the field.
	 
	//if( $this->debug ) {
	//	print "<pre>";
	//	print "Initialized Fields<br>";
	//	print_r( $this->fields );
	//  	print "</pre>";
	//}

}


 		/*
		 * Submitted Status
		 *
		 */
		 
function submitted() {
// IMPORTANT! Needs to detect value from GET or POST forms.
	if( $_REQUEST['submitted'] || $_POST['submit'] || $_GET['submit'] ) {
			// or return value of submit button, it's so-called caption
			// return $_POST['submit'] ? $_POST['submit'] : $_GET['submit'];
			return true;
		} else {
			return false;
		}
}

/*
 * Validate
 * Validate inputs and check if required.
 */

function validate() {
//print "Calling validate()";
		// clear flags
		$this->fail_validate = 0;
		$this->fail_require = 0;
		
		$msgengine = new ooFormMessages;
		 
	// for each of the fields by name, examine values
	// of params that exist for that name, if any
	
	// IMPORTANT change, what we really need to do now that we have a data structure representing the vlaues of form fields, is when a form is submitted, update the field values with the values from the CGI parameters.
/*

DEFAULTS --> form fields --> CGI PARAMS --> FIELD VALUES --> so that at this point, after submission, the params and field values are in sync, so that if you say $form->field() to the value, it is the same as the param just submitted.

This may be why FB uses two passes, one to redistribute the parameter values over the corresponding form fields. CGI PARAM -> FIELD VALUE

Then it uses the field values to check against the regular expressions. I think I am still checking against the param and not updating the fields.

Where do I set the field value to the cgi value?

*/
	
	// debug
	//print "Fields to validate: ";
	//print_r( $this->fields_list );
	
	 foreach ( $this->fields_list as $field_name ) {
		
	 	// debug
		//if( $this->debug == DEBUG_VERBOSE ) {
		//print '<pre style="border: 1px solid #ccc; background: #eee">';
		//print "Processing Field\n";
		//print "Field: " . $field_name ."<BR>";
		//print "Value: " . $this->value($field_name) ."<BR><br>";
		//print "Fields<br>";
		//print_r( $this->fields );
  		//print "</pre>";
		//}
		
		/* My first way of thinking is to validate the values of CGI parameters. I'm thinking of a firewall, that I want to get the values first and check them. But ooForm is the firewall. So it would not make any difference if all parameters were redistributed to field values now that we keep a data structure of field properties, one of which is the value.
		The regexes could be run against the field values instead of the cgi parameter values.
		*/
		
		// get the generic dynamic value for this field
		// which is either the default stored in props or from cgi
		//$val = $this->value($fieldname);
		// look down to validate section for where this really gets used
		
		//if( $val == '' ) {
		// Note, required validation fails unless you check against cgi params, can't use value function becuase it may be sticky or default value, for required, we must check params to see what is being submitted and what is not, because value may be giving us the default value or the cgi value depending on sticky status.

/**
 * If this field is empty, test for required status,
 * otheriwse check for validity.
 *
 * FIXME: I think this should use empty()
 */

		if( $this->params_list[$field_name] == '' ) {
//		if( empty( $this->params_list[$field_name] ) ) {
		
				//if( $this->debug == DEBUG_VERBOSE ) {
				//print "Param: " . $field_name ." is empty<BR>";
				//}
				
			// we know the field is empty, check if required
			if(	in_array($field_name, $this->required_list) ) {
			
			
				// set flag
				$this->fail_require = 1;
			
				// if empty and on required list, field is invalid
				$this->fields[$field_name][invalid] = 1;
			
				// IMPORTANT: if the intention is to render the form again with fields filled in for correction and error messages beside the fields, then we must store the required errors, not just stop at the first one.
				// to prepare for that I will try setting the invalid flag in the stored fields array for this field

				$this->fields[$field_name][required] = 1;
			
				// set the error message for this field
			
				// move to messages config
				//$temp = "<span>Please enter a value for the '$field_name' field.</span>";
				//$this->fields[$field_name][error] = $temp

				//print "Setting Error Message:";
				//print $msgengine->message('field_required', $this->fields[$field_name][label]);
				
				$this->fields[$field_name][error] = $msgengine->message('field_required', $this->fields[$field_name][label]);
			
			
			
				// moreover, I suppose you don't need to use this as a flag, you don't need to store failed required status, if you know the field is required, then after validation, you can then check that each required field has a value and compute the intersection with falied validate fields. No flag needs to be set, so what am I doing here?

				// debug
				if( $this->debug == DEBUG_VERBOSE ) {
					print "Parameter " . $field_name ." failed required.<BR>";
				}
				
			}
			
		} else {
		
		/*
		 * Validate Fields
		 *
		 */
		 
		 // Note: if a field is empty, we don't check it for validity, we only check non-empty fields for validity, we check empty fields to see if they are required, once that check is done, we don't have to check if valid.
		
		/* LOOK HERE! This is where we get the value to validate from the PAREMETER LIST instead of from the field value. This is legacy practice. It should get it from the field value and field values all "redistributed" prior to validation.

		Still does not answer where the value is being set.
		*/
		
		// now we get value at top before conditional
		//$val = $this->params_list[$field_name];
		
		if( $this->debug == DEBUG_VERBOSE ) {
		print "Validating " . $field_name ."<BR>";
		print "With Value: " . $this->value($field_name) ."<BR>";
		}
		
		// get regex
		if( array_key_exists($this->validate_list[$field_name], $this->rules_list) 
			&& preg_match('/_[a-zA-Z]+$/', $this->validate_list[$field_name])
			) {
			if( $this->debug == DEBUG_VERBOSE ) {
			print "Using rule";
			}
		       $regex = $this->rules_list[$this->validate_list[$field_name]];
		    } else {
			if( $this->debug == DEBUG_VERBOSE ) {
			print "Using user defined rule";
			}
		       $regex = $this->validate_list[$field_name];
		    }
			if(  $regex != '' && (! preg_match( $regex, $this->value($field_name))) )
			{
			// error validation
			$this->fail_validate = 1;
						
			// experimental code to set invalid flag for this field
			$this->fields[$field_name][invalid] = 1;
			// and error message
			//$this->fields[$field_name][error] = $this->messages_list[$field_name];
// set through class

/**
 * Validate message trumps required message.
 */
 
// TEMP DISABLED
$this->fields[$field_name][error] = $msgengine->message('field_invalid', $this->fields[$field_name][label]);
			
			}
		}
	
	} // end foreach
	
	// debug	
	//print "Fail Validate: ". $this->fail_validate ."<br>";;
	//print "Fail Require: ". $this->fail_require ."<BR><br>";;
		
		
	if( $this->fail_validate
	|| $this->fail_require ) {
		return false;
	} else {
		return true;
	}
	
	
} // end function

 
		/*
		 * Trusted Source
		 *
		 */
		 
// This is true if the GET/POST information comes from a trusted source

function trusted() {
	//-----------------------------------------------------------
	// Check for trusted host
	if(getenv('HTTP_REFERRER')) {
		$ref_url_parts=parse_url(getenv('HTTP_REFERRER'));
		} else {
		$ref_url_parts=parse_url(getenv('HTTP_REFERER'));
		}
	if($ref_url_parts['host'] !== $this->accept_host) {
		return false;
		//print "ERROR - You do not have permission to access this site remotely.";
		//exit;
	} else {
		return true;
	}
}

/**
 * Helper function for value() from php manual entry by mike-php at emerge2 dot com
 */
 
function stripslashes_array( $given ) {
  return is_array( $given ) ? array_map( 'stripslashes', $given ) : stripslashes( $given );
}
 
function value( $field_name ) {
// private method
// the purpose of this function is to return the dynamic
// auto sticky value of a field

// Todo: value is returning sticky value with slashes
// when magic quotes is enabled. Need to strip slashes
// at some point from CGI values.

	if( $this->sticky ) {
		// return cgi param value, cgi beats default
		
		/* Note: params_list is a copy of $_POST or $_GET or
		   or $_REQUEST array.
		   
		   */
		   
		   // FIXME: for the framework integrated version
		   // magic quotes will be assumed to be disabled
		   // REMOVE ALL CODE HANDLING MAGIC QUOTES

				// handle magic quotes nightmare, grrr!		  
		if ( get_magic_quotes_gpc() ) {
		/**
		 * \remark params_list[key] holds the value for a particular CGI parameter. It does not need to specify a second key to get the value. Just to clear up a point of possible confusion, as to why it does not say params_list[key]['value'] like accessing a field.
		 */
			$value = $this->params_list[$field_name];
			// this does not handle multiple select arrays
			$value = $this->stripslashes_array( $value );
		} else {
			$value = $this->params_list[$field_name];
		}
	
	return $value;
		
	} else {
		return $this->fields[$field_name]['value'];
	}
}


/**
 * field
 * get the value of a form field
 */

 	function field() {
	/* Note: This function depends on special PHP functions, which cannot be used outside of a function definition. The behavior of the functions is somewhat inconsistent with the behavior of normal functions.
	 */
	$numargs = func_num_args(); // function cannot be used directly as a function parameter
	   
	if ($numargs > 1) {

		/**
		 * Field Assign
		 *
		 */
			   
		$arg_list = func_get_args();
		   
		$field_name = $arg_list[0];
		$field_value = $arg_list[1];
			   
		/* I suppose this would work too
		   $field_name = func_get_arg(0);
		   $field_value = func_get_arg(1);
		*/
		
		// debug	
		//print "Assigning value " 	. $field_value ." to ". $field_name ." field<br>";
	
		$this->fields[$field_name]['value'] = $field_value;
		   
	} else {
	
	/**
	 * Field Retrieve
	 *
	 */
			
	$arg_list = func_get_args();

	$field_name = $arg_list[0];
		   
	/* I suppose this would work too
	   $field_name = func_get_arg(0);
	 */
	 
	return $this->value( $field_name );
	   }
	}
	

// todo, we may not be able to pass template file here, may need to pass options per engine, although maybe here we can safely specify the template file for the form generically usable by any engine?
function render($template_file, $sticky) {
	$this->sticky = $sticky;
	return $this->templateobj->render($this, array( 'template' => $template_file));
}

// this is not an accessor function because fields are not yet objects
// but it acts like an accessor
function label( $field_name, $label ) {
// okay, the reason this is failing, is that I think it is being called before the array is being created. The fields array does not have any contents until validate() method is invoked. So during form setup, it has no content.
// what I did was adde a labels list so the calling program can create the labels as an assoc. array before validate is invoked.

// debug
//print "Setting Label: $label on Field: $field_name";
$this->fields[$field_name][label] = $label;
//print "Label Set To: ". $this->fields[$field_name][label];
		
}

function debug( $text, $value ) {
print "<p>(Debug) $text: $value</p>";
if( is_array( $value ) )
 {
 	print_r( $value );
 }
}

// IMPORTANT Set options array.
/* Expects associative array of option values and labels
 array(
 	'va' => 'Virginia',
	... etc. ...
	)
 */
function options( $field_name, $options ) {
// could test to see if this is an array here, warn if not?
	
	$this->fields[$field_name]['options'] = $options;
	
	//$this->debug( "Fieldx Value", $this->fields[$field_name]['value'] );
}

// this is function to set field properties
// one stop shopping style
// it not an accessor because fields are not objects
// this is horribly complicated syntax in PHP
// we need a simple name=value function to set
// the value of a field
function setfield( $field_name, $options ) {

//if( $this->debug ) {
//print "<pre>";
//print "Options:\n-----------------------------\n";
//print_r( $options );
//print $options['name'];
//print $options['value'];
//print "\n--------------------------------------\n";
//print "</pre>";
//}

/*
 Options (to this function, not an HTML menu option) is an associative array, which gives
 you great flexibility in setting any number
 of options you want.
 
	array(
		'name' => 'email',
		'value'	=> 'foo@bar.com',
		'label' => 'Email Address',
		'required' => 1
		);
 */
 
$this->fields[$field_name]['name'] = ( $options['name'] ) ? $options['name'] : '';

$this->fields[$field_name]['value'] = ( $options['value'] )	? $options['value'] : '';

if( $this->debug ) {
print "<pre>";
print "Value (option): " . $options['value'] . "\n";
print "Value: " . $this->fields[$field_name]['value'] . "\n";
print "</pre>";
}

$this->fields[$field_name]['label'] = ( $options['label'] )	? $options['label'] : '';

$this->fields[$field_name]['required'] = ( $options['required'] ) ? $options['required'] : '';

/*
the short circuit are a little space saving, but not too much, perl is much more elegant
this might be clearest
 	if( $options['name'] ) {
		$this->fields[$field_name][name] = $options['name'];
	}
	if( $options['value'] ) {
		$this->fields[$field_name][value] = $options['name']
	}
 	if( $options['name'] ) {
		$this->fields[$field_name][label] = $options['name']
	}
	if( $options['name'] ) {
		$this->fields[$field_name][is_required] = $options['name']
	}
 	if( $options['name'] ) {
		$this->fields[$field_name][invalid] = $options['name']
	}
*/
	//$this->fields[$field_name][error] ???

}


function tmpl_param( $name, $value ) {
	$this->templateobj->assignp($this, array( 'name' => $name, 'value' => $value));
}

/******************************************************************
 *                        Accesor Functions/Methods
 * These access the "member variables" or member properties.
 */
 
 	function params( $array ) {
		$this->params_list = $array;
		}
		
	/**
     * specify field names recognized by handler
     *
     * @param array list of fields
     */
	function fields( $array ) {
		$this->fields_list = $array;
		}
		
	/**
     * specify list of fields and rules to validate against
     *
     * @param array associative list of fields and validation rules 
     */
	function validate_fields( $array ) {
		$this->validate_list = $array;
		}
		
	/**
     * specify required fields
     *
     * @param array list of required fields
     */	
	function required_fields( $array ) {
	
		$this->required_list = $array;
	// with foreach and the field props we really don't need a
	// list of required fields do we?
	foreach ( $array as $field_name ) {
	
		$this->fields[$field_name][is_required] = 1;
	}
		
		}
		
	/**
     * specify field error messages
     *
     * @param array associative array of fields and messages
     */
	function messages( $array ) {
		$this->messages_list = $array;
		}
		
	function accept_host( $domain ) {
		$this->accept_host = $domain;
		}
		
    /**
     * returns an array containing
     * a list of recognized field names
     */	
	function get_fields( ) {
		return $this->fields_list;
		}
		
		function dbg() {
			//print "<h3>Debugging Information</h3>";
			//print "<pre>";
			
			//print "Field props";
			//foreach ($this->fields_list as $f ) {
			//	print_r($this->fields);
			//	}
			//print "Fields<br>";
			//print_r( $this->fields_list );
			//print "Parameters<br>";
			//print_r( $this->params_list );
			//print "Validate<br>";
			//print_r( $this->validate_list );
			//print "Required<br>";
			//print_r( $this->required_list );
			//print_r( $this->messages_list );
			//print $this->error;
			print "End Debugging";
			print "</pre>";
		}
		
function test() {
print "Test";
return true;
}
			
} // end class ooForm

?>