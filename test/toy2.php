<?php


// test

$data = array(1=>'Food',2=>'Music',3=>'Women',4=>'Agriculture',5=>'Regional');


$form_data['subjects']['options'] = $data;

$form_data['subjects'] = 'foo';

/**
 print_r() shows that the 'options' array is overwritten when assgning 'foo', so this does not work like a multidimensional array.

We know that arrays in PHP are actually list collections or maps.

So

$arr['a']['b'] = 'foo'

looks like this

array(
	'a' =>	array(
			'b' => 'foo'
			)
)
	
If you then say

$arr['a'] = 'bar'

it looks like this

array(
	'a' => 'bar'
)

I think this is what is happening.

I need a structure like this:

array(
	'subject' => array(
			'options' = array(1=>'Food',2=>'Music',3=>'Women',4=>'Agriculture',5=>'Regional')
			'selected' => 'foo'
			)
)
 
 
 The problem is I have already defined a structure like this:
 
$arr = array(
 	'title' => 'Title',
	'description' => 'Some text.',
	'subject' => 123
 )
 
 where each field has an entry in the array.

 How can you say $arr['subject'] and get '123' and also say $arr['options'] and get an array of values? If the value of 'subject' is '123' that's not going to be possible. The value can either be '123' or an array.

You could make the array like this (similar to above):

$arr = array(
	'subject' => array(
			'options' = array(1=>'Food',2=>'Music',3=>'Women',4=>'Agriculture',5=>'Regional')
			'value' => '2'
			)
)

The options are accessed $arr['options'] and the value $arr['value'], but this sucks because you would then need to say

{$form.subject.value} all the time just to get a value.

What if only options were like this, but non-options form fields would be accessible normally?

{$form.title}

Something like this:

$arr = array(
	'title' => 'Title',
	'subject' => array(
			'options' = array(1=>'Food',2=>'Music',3=>'Women',4=>'Agriculture',5=>'Regional')
			'value' => '2'
			)
)

{$form.title} == Title

{$form.subject.options} == Food, Music, Women, Agriculture, Regional
{$form.subject.value} == 2

It would okay to only require the "long form" when the field is a multi-value one.

 
 */

print "<pre>";
print_r( $form_data );
print_r( $form_data['subjects'] );
print_r( $form_data['subjects']['options'] ); // Very strange behavior
print "</pre>";

?>
