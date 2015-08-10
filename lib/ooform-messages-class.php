<?php
/**********************************************************************
 * Begin: Wednesday, November 03, 2004
 * Revised: Sunday, April 03, 2005
 * Credits: Steve Knoblock. This project would not have been possible
 * without the generous help of Nate Wiger.
 **********************************************************************/
// part of class ooForm package
// useless for anything else

/* IMPORTANT! ooForm in previous versions supported messages on a per field basis, so that the email field error message would specifically say 'not a valid email address' instead of a generic 'this field is invalid' message, which is unacceptable to many of my clients.

I will start with the generic messages.

*/


class ooFormMessages {

// default generic messages
var $messages;
var $start_tag = '<span>'; // avoids changing tag structures and syntax
var $end_tag = '</span>';

// constructor
function ooFormMessages() {
// first %s is open tag, second %s is field label, third %s is close tag
	$this->messages = array(
	    'field_required' => '%sPlease enter a value for the %s field.%s',
	    'field_invalid' => '%sYou must enter a correct value for the %s field.%s',
	 );
}

//( &$ooform, $params )
	function message( $key, $label ) {
	// what we want to do is add a label property to the field, for use in this message

	// debug
	//print "Key: $key\n";
	//print "Label: $label\n";
	//print "Message Key: " . $this->messages[$key];

		$message = sprintf($this->messages[$key], $this->start_tag, $label, $this->end_tag );
	return $message;
	
	}

}

?>