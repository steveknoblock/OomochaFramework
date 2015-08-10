<?php

/**********************************************************************
 * Begin: Wednesday, November 03, 2004
 * Revised: Sunday, April 03, 2005
 * Credits: Steve Knoblock. This project would not have been possible
 * without the generous help of Nate Wiger.
 **********************************************************************/

// Modified Saturday, April 16, 2005 to support automated field_fieldname placeholders
// Mod 15th to set value_fieldname for field value placeholders
// Modified Wednesday, April 20, 2005 is something wrong with form_action placeholder?

// TODO rename this file to ooform.smarty.class.php etc. and the class should be renamed to reflect each template engine if you want to be able to switch template engines at will or to run more than one at once, otherwise, just replace this file and continue to use ooFormTemplate as the class name, if you do want to switch template engines at will, ooForm will need to be modified to sleect the appropriate template engine adaptor.

// IDEA IS FOR FRAMEWORK AND FORM TO EMPLOY A TEMPLATE ADAPTOR TO WRAP TEMPLATE ENGINE

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
print "<p>SMARTY_DATA" . SMARTY_DATA;
	$this->template_dir = SMARTY_DATA . '/templates' . $dir;
	print "<p>Smarty Template Dir: ". $this->template_dir;
}

	function render ( &$ooform, $params ) {
	
	// debug
	//print "ooForm debug: Rendering Template: " . $params['template'];
	
	/* Note: This function must be generic enough to accept the reference to the form object and any parameters that need to be passed to the template engine in its own format. So the arugments are first the reference to the form handler object and second, an array of parameters to pass to the template engine.
	 */

	
	foreach ( $ooform->get_fields() as $fieldname) {
	
	$value = $ooform->field( $fieldname );
	
	/* Debug
	print "<p>(Debug) Template Adapter Processing Field</p>";
	print '<p>(Debug) Field: '. $fieldname .'</p>';
	print "<p>(Debug) Value: $value</p>";
	*/
	
	/* Note: The assignment of error messages cannot take place in the form class, it must take place in the template class. This is because the form hanlder must work with the template engine through this template adapter.
	 */
	
	// CGI::FormBuilder of the same looks like this
	//$tmplvar{"error-$field"} = $field->message if $field->invalid;
	
		// old
		//$this->assign( 'error_' . $fieldname, $ooform->error_list[$fieldname] );
	
		if( $ooform->fields[$fieldname][invalid] == 1
			|| $ooform->fields[$fieldname][is_required] == 1 ) {	
			$this->assign( 'error_' . $fieldname, $ooform->fields[$fieldname][error] );
		}
		
		$this->assign( 'label_' . $fieldname, $ooform->fields[$fieldname][label] );

/* Here we assign the value contained in the form field to the template placeholder. This is the function implemented by the template engine we built the adapter for.

	Why does this fail for array values? Is it?

*/

/* Handle fields of type select menu. This assigns an associative array of option values and labels for a select menu to the template placeholder. This is then used on the template. This is where the adapter really shines. Smarty ships with a special function to handle select menus in one construct by accepting an associative array of option values and labels. Other template engines might require the adaptor to do more work, to setup for a "template loop" construct such as the Smarty section, which I could also use in this case.

One reason for all this: It is possible to assign option values and labels to fields given to the form hanlder that are 'dummy' fields, but this is inelegant and hackish. It works, but speicfies nonexistient form fields as fields. Yuck.

 */

 
 // MUST ASSIGN BEFORE MULTI-VALUE FIELDS
 
 $form_data[$fieldname] = $ooform->field( $fieldname );
 
 	// debug
 	//print "<p>(Debug) Options Field: $fieldname</p>";

// print_r( $ooform->fields[$fieldname][options] );
 
 // I could store form type, but that may not be necessary given the system does not _generate_ form fields, but works through a template engine.
if( ! empty( $ooform->fields[$fieldname][options] ) ) {
	
	// debug
	//print "----------------------------------------------------";
	//print "<p>(Debug) Field: $fieldname</p>";
	//print "<p>(Debug) Options:</p>";
	//print_r( $ooform->fields[$fieldname][options] );
	$this->assign( $fieldname . '_options', $ooform->fields[$fieldname][options] );

/**
Problem:

okay, $form.subject needs to be a scalar like

{$form.subject} == 2

but there also must be a "behind the scenes" array of option values. Some template variable must contain them.

{$form.subject.options}


You can see it's not being assinged:

{$form}	Array (5)
id => 1
title => titleme
link => bar
description => baz
subject => 2

Where is 

$form_data[subject][options]

?
*/
	// try this for 'form' array method
	$form_data[$fieldname] = array(
	
		'options' => $ooform->fields[$fieldname][options],
		'value' => $value
		);
		
	//print "OPTIONS:";
	//print_r($form_data[$fieldname]['options']);
	//print_r($form_data[subject][options]);
	
	// this is for errors, sorry
	// OR
	//$form_data[errors][$fieldname] = $ooform->field( $fieldname );

/*

assign selected placeholder

this is what smarty expects

{html_options options=$film_options selected=$option_selected }

we (the program and me) created film_options automatically above

I need to send the current field value to the placeholder for selected option.

$this->assign( $fieldname . '_selected', $ooform->fields[$fieldname][value] );

my($slct, $chk) = ismember($o, @value) ? ('selected', 'checked') : ('','');
            debug 2, "<tmpl_loop loop-$field> = adding { label => $n, value => $o }";
            push @tmpl_loop, {
                label => $n,
                value => $o,
                checked => $chk,
                selected => $slct,
            };

*/

// delete this line
//$value = $ooform->field( $fieldname );
// debug
	//print '<p>(Debug) Field: '. $fieldname .'_selected</p>';
	//print "<p>(Debug) Selected Value:</p>";
	//print $value;
	
$this->assign( $fieldname . '_selected', $value );

}

// look down, I think the next statement should be in an else clause, if it's an option, we don't need to assign, it's already been assigned to _selected


// debug
//print "<p>(Debug) $fieldname: $value</p>";
//if( is_array( $value ) )
 //{
 //	print_r( $value );
 //}
 

/* Here we assign values to placeholders from form property values.

the value for title field in properties is transferred to the template thorugh a placeholder named after the fieldname.

so field title
submits "Title"
and then field title property called value
is set to "Title" from cgi parameters
then the value of the field is
transferred to template placeholder
the placeholder name is the name of the field,
which is the name of the form input, etc.

Now, it might be preferable, like CFB, to give these placeholders the name

value_[fieldname]

[fieldname]_value

instead of just the field name, sort of like a OO properties syntax. Like the field error and field label placeholders.

error_title
label_title
value_title
(options_title ?)

Here, it grabs the field value through invoking the field() method from ooForm.

*/ 

 // Modified Saturday, April 16, 2005 to support automated value_fieldname placeholders
		//print "Assinging Field: " . ('field_' . $fieldname) ."<br>";
		//print "With Value: " . $value ."<br>";
		$this->assign( 'value_' . $fieldname, $ooform->field( $fieldname ) );
		
	
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
		 // works!
		 // moved up
		 //$form_data[$fieldname] = $ooform->field( $fieldname );
			
		
// old
//$this->assign( $fieldname, $ooform->field( $fieldname ) );
	}

	// assign form_data, works!
	$this->assign( 'form', $form_data  );
	
	// special system placeholders
	//$this->assign( 'form_action', $this->action() );
	

		
	// test
	//$this->assign( 'here', "You are here" );
	
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