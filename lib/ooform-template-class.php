<?php

/**********************************************************************
 * Begin: Wednesday, November 03, 2004
 * Revised: Saturday, June 03, 2006
 * Credits: Steve Knoblock. This project would not have been possible
 * without the generous help of Nate Wiger.
 **********************************************************************/

// Modified Saturday, April 16, 2005 to support automated field_fieldname placeholders
// Mod 15th to set value_fieldname for field value placeholders
// Modified Wednesday, April 20, 2005 is something wrong with form_action placeholder?

// TODO rename this file to ooform.smarty.class.php etc. and the class should be renamed to reflect each template engine if you want to be able to switch template engines at will or to run more than one at once, otherwise, just replace this file and continue to use ooFormTemplate as the class name, if you do want to switch template engines at will, ooForm will need to be modified to sleect the appropriate template engine adaptor.

// part of class ooForm package
// useless for anything else

/*
 * Credits: Steve Knoblock. This project would not have been possible
 * without the generous help of Nate Wiger.
 */

 /*
 only needs location of smarty
 Depeds on your platform.
*/

// FIXME: THESE DIRECTORIES SHOULD CORRESPOND TO THE SMARTY BEING USED BY THE FRAMEWORK
// THIS SHOULD BE INTEGRATED INTO THE FRAMEWORK


// this would be better, as the Smarty manual says
define('SMARTY_DIR', '/usr/home/cityg/tools/Smarty/'); // SMARTY_DIR Must end with a trailing slash
require_once SMARTY_DIR.'Smarty.class.php';

// this is my own custom constant, not a Smarty defined one
define('SMARTY_DATA', '/usr/home/cityg/web/phphelp/Smarty'); // NO TRAILING SLASH unlike SMARTY_DIR

// document root must be defined
// this is the location
/*

Am I using this on Folkstreams? This is really wack because SMARTY_DIR is a Smarty internal
value. It should not be set other than to include the Smarty library code, correct? Well, I think on Folkstreams Smarty code and Smarty data are in the same directory, so this is okay, but not for all Smarty installations. The template adaptor only works on Folkstreams or any site that places all the Smarty data out on the web in the Smarty code folder.
*/


class ooFormTemplate extends Smarty  {

var $moddir = ''; // define module dir should templates be grouped into modules

function ooFormTemplate( ) {

// smarty setup through extended class
// you cannot create the template engine object
// from outside the context of the form handler
// and expect this adapter to work The form handler
// must create and control the template engine object
// and set its properties
// we set basic folder info for smarty here
// SMARTY_DIR should be SMARTY_DIR

/**
 Framework: The problem is that this original ooform code assumes Smarty is
 all in one directory. On this installation, Smarty is in
 
 /usr/home/cityg/tools/Smarty
 
 and Smarty data (templates, cache, config) are in
 
 /usr/home/cityg/web/phphelp/Smarty/templates/list
 
 Smarty is installed once and each website/application
 using Smarty has its own directory.
 
 Or is it a problem? We alreayd include the Smarty engine, the other stuff should be in the
 SMARTY_DIR. Why is it failing?
 */
 
 /**
  SMARTY_DIR:
  	The full file system path to the location of Smarty class files. It must end with a slash.
	
	This constant must not be overriden by your code unless it is for a specific reason and
	must take into account the effect it has on Smarty. SMARTY_DIR is used by Smarty internally.
	
  */
 
/*
This code assumes Smarty code is in the parent directory of Smarty data.
Smarty/...smarty library files...
Smarty/templates
Smarty/...
Smarty/cache

SMARTY_DIR _should not_ be used for specifying the Smarty data directories unless you know they will reside in the same directory as the Smarty class files.

*/
$this->template_dir = SMARTY_DATA . '/templates' . $this->moddir;
$this->compile_dir = SMARTY_DATA . '/templates_c';
$this->config_dir = SMARTY_DATA . '/configs';
$this->cache_dir = SMARTY_DATA . '/cache';

/**
 * enable for debugging
 */
//$smarty->compile_check = true;
//$smarty->debugging = true;
}


// adds a render function to bigfoot, which only has a fetch function to fetch the rendered template. Why was it not called render? Because it was to have fetch and display, one returning the rendered output and one that sent it directly to the browser (print).

// assign an arbitrary placeholder in template not mentioned in form field list
	function assignp ( &$ooform, $params ) {
	
	// params are placeholder name and value
	
	$this->assign( $params['name'], $params['value'] );
	
	}
	
	// NEW for framework
	// sets module directory for template adaptor
function set_mod_dir( $dir ) {

// Debugging
// print "<p>SMARTY_DATA" . SMARTY_DATA;
	$this->template_dir = SMARTY_DATA . '/templates' . $dir;
// Debugging
// print "<p>Smarty Template Dir: ". $this->template_dir;
}

function render ( &$ooform, $params ) {
		
	// debug
	//print "ooForm debug: Rendering Template: " . $params['template'];
	
	/* Note: This function must be generic enough to accept the reference to the form object and any parameters that need to be passed to the template engine in its own format. So the arugments are first the reference to the form handler object and second, an array of parameters to pass to the template engine.
	 */
	 	
	foreach ( $ooform->get_fields() as $field_name) {
	
	$value = $ooform->field( $field_name );
	
		/*

		Handle Selected values.
				
		
		Both "checked" checkbox and "selected" radio buttons are treated as
		selected.
		
assign selected placeholder

this is what smarty expects

{html_options options=$film_options selected=$option_selected }

we (the program and me) created film_options automatically above

I need to send the current field value to the placeholder for selected option.

To determine if a checkbox should be checked, Smarty accepts a 'selected' parameter to the html_checkboxes custom function. If the value of a checkbox in the array of checkbox inputs given to it matches the value given to the selected parameter, the checkbox input is generated as selected.

(BTW all this magic is getting so close to just specifying a form and generating the XHTML from code and tossing the template it seems ridiculous to do things this way. On the other hand, you don't want to dig through code to fix a tiny HTML error or change, although XHTML is getting very stable and unlikely to change often, so one could assume that CSS is always available to style the XHTML output and any small changes to XHTML standard could be fixed in the code.)

$this->assign( $fieldname . '_selected', $ooform->fields[$fieldname][value] );

my($slct, $chk) = ismember($o, @value) ? ('selected', 'checked') : ('','');
            debug 2, "<tmpl_loop loop-$field> = adding { label => $n, value => $o }";
            push @tmpl_loop, {
                label => $n,
                value => $o,
                checked => $chk,
                selected => $slct,
            };

			
			Okay, here is the problem.
			
		For select menus, the selected array must hold the actual values of the selected items. IOW the Smarty routine matches up the values against the options and marks them selected automatically where they match. This means $form.fieldname.selcted must be the _value_ of the selected item.

For checkboxes, using the Smarty routine, I think it may be the same. The selected values should be the actual values not the 'selected' attribute.

The .selected value is working for select menus and radio buttons. It supplies the actual value of the field to the Smarty "custom_function" generating the HTML represnting the form field.


What is interesting, is Smarty seems to be generating 'checked'

Okay, checking the W3 HTML4.0 spec, an INPUT element uses the checked attribute.


<input type="radio" name="radio" value="2" checked="checked" />

The OPTION element uses the 'selected' attribute.
<option ... selected="selected" />

I think the easiest thing to do is follow the pattern used by HTML Template to create separate checked and selected values.

This would mean changing code so that 'selected' only applies to select menus and 'checked' applies only to checkbox (single checkboxes are a group of one) and radio groups. Currently, 'selected' is used for radio groups. The problem is, I already chose to handle radio groups the same as menus. Why can't the checkbox groups take the same value?

In Smarty:

html_checkboxes: options are the associative array of values and their corresponding human readable values; selected is an array containing the selected values;

html_options: options is an associative array of values and their corresponding human readable values; selected is an array of selected values

html_radios: options is an associative array of values and their corresponding human readable values; selected is NOT an array but a scalar string value that matches the selected radio button

In all three cases, the options are represented as an associative array. With regard to selected values, only radio button groups do not use an array for selected values, because only one radio button may be selected at a time.

As long as I use the Smarty functions to generate HTML, I do not have to worry about the difference between checked and selected.

'selected' is either a scalar or an array. Smarty knows what to do with it and generates the approprite 'checked' or 'selected' attribute.

The problem is there is a matching issue with Smarty. I am not sure what is causing it. But options accept a value and a label like this '1' => 'bar', '2' => 'foo' and radios accept the same, they convert this into an option with a value attribute from the assoc. index and a label with the assoc. value. This is not working for checkboxes.

I think the problem is that checkboxes require the index value from the associative array for matching.

This is what is being passed to Smarty:

options => Array (5)
    1 => Food
    2 => Music
    3 => Women
    4 => Agriculture
    5 => Regional
  value => 2
  selected => 2
  
  
*/

	//$selected = empty($value) ? '' : 'selected';
	$selected = $value;
	
	/* Debug
	print "<p>(Debug) Template Adapter Processing Field</p>";
	print '<p>(Debug) Field: '. $field_name .'</p>';
	print "<p>(Debug) Value: $value</p>";
	*/
	
	


// delete this line
//$value = $ooform->field( $fieldname );
// debug
	//print '<p>(Debug) Field: '. $fieldname .'_selected</p>';
	//print "<p>(Debug) Selected Value:</p>";
	//print $value;
	

	////////////////////////// BEGIN NEW TEMPLATE ASSSIGNMENT CODE ///////////////////////

	// this property array differs from the previous ???
	// all errors reset
	
	// NEW CODE FOR $form.field.error
	if( $ooform->fields[$field_name][invalid] == 1
			|| $ooform->fields[$field_name][is_required] == 1 ) {	
		$error_message = $ooform->fields[$field_name][error];
	} else {
		$error_message = '';
	}
	

/*

Important: to produce the correct {$form.field} variables the array must be built for single value fields and then for multi-value fields.
Be careful to not overwrite fields, but if their names are unique, which they must be, then it does not matter.
*/

// TODO: could these assignments be done all at once in a single array? error, value, optins, label ? I suppose not because some must be conditional.

			
	 // Okay, this assigns single value (non select/radio/checkgroup) fields.

	 /*
	  * Supports {$form.title} shorthand.
	  */
	  
 //$form_data[$field_name] = $ooform->field( $field_name );

/*
I could get rid of this check if the shorthand were replaced. Fields would require 'value' property:

{$form.title}
becomes
{$form.title.value}

There is no way I can think of to make this an array and have a value appear at {$form.fieldname}

The form field may only have a single value, either a scalar or an array.

I have commented out the unnecessary code to try this. It works, so I think I will use it.

*/ 
 //if( ! empty( $ooform->fields[$field_name][options] ) ) {
 /*
  * Supports template variables of the form: {$form.fieldname.options} and {$form.fieldname.value} for multi-valued form fields. Example:
{$form.subject.options}
  */
	$form_data[$field_name] = array(
		'error' => $error_message,	
		'label' => $label,
		'options' => $ooform->fields[$field_name][options],
		'value' => $value,
		'selected' => $selected
		);
//}
		
	////////////////////////// END NEW TEMPLATE ASSSIGNMENT CODE ///////////////////////


/* Here we assign values to placeholders from form property values.
Here, it grabs the field value through invoking the field() method from ooForm.

*/ 

	
	
		/**
			Testing output value in smarty variable form like this
			{$form.fieldname}
			So that all form fields are part of a 'form' array.
			This way I can do away with the 'value_' prefix to keep form values separate
			from other template variables.
			
			This would radically alter the way form field template variables are
			handled. Instead of creating an individual variable for each form
			field, a single associative array containing all form fields would
			be generated.
		 
		 	The following is a very hackish experiment, to build an array and then
			assign it. This may be unnecessary and could perhaps take the array
			used by form object straight.
			
			
			What I'd like to see is
			
			{$form.title}
			
			
			{html_options options=$form.streamformat.options selected=$form.streamformat.selected }
			
			IOW a heirarchy of arrays or objects that
			
			form object . fieldname . options / selected
			
			Also
			
			{$form.streamformat.error}
			
			Is this possible?
			
			Or perhaps
			
			{$form.errors.streamformat}
			
			would be simpler?
			
			<select name="streamformat" id="StreamFormat" tabindex="7">
	{html_options options=$streamformat_options selected=$streamformat_selected }
	</select><br><span class="instruction">(Format of streaming media: Real, MPEG, Quicktime)</span> <span class="fielderror">{$error_streamformat}</span>
			
			
			In all cases, a form value is assigned to the _form_ object, which
			then delegates by asking the template engine object to assign to
			the template. This is why the template adaptor is necessary, the
			application assigns the value to the form $form->field(label, val)
			and the form object delegates the task of assigning the value to
			the template variable by invoking its assign on the same name/value
			pair. This is where it happens, in the template adaptor, which
			enables the form object to work with any number of different
			template engines, as long as the adaptor is tailored to the engine. This does not occur in the application nor in the form object class.
		 */
		
	}

	// assign form_data, works!
	$this->assign( 'form', $form_data  );
	
	// special system placeholders
	//$this->assign( 'form_action', $this->action() );
	
	
	// debug
	//print "Smarty Template Dir: -->" . $this->template_dir ."<--";
	//print "Rendering Template: " . $params['template'];
		
		/* Unfortunatley, debugging console is only available when displaying the template through Smarty. For the purposes of this adapter, we cannot do that.

		$this->debugging=true;
		
		{$debug}
		
		does not work either through fetch, at least I couldn't get it to work.
		
		*/
	return $this->fetch( $params['template'] );
	
	}

}

?>