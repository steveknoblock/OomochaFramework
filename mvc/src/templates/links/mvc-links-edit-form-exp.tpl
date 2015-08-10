<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Untitled</title>
</head>

<body>


mvc-links-edit
{* debug *}

<p><strong>Form Data:</strong></p>

{$form.title}

<form action="index.php" method="post">
<input type="hidden" name="action" value="links/saveLink">

<input type="text" name="title" value="{$form.title}"><br>
<input type="text" name="link" value="{$form.link}"><br>
<input type="text" name="description" value="{$form.description}">

Subjects:<br>
1: {$form.subject.options[1]}<br>
2: {$form.subject.options[2]}<br>

{section name=option loop=$form.subject.options}
	option: {$form.subject.options[option]}<br>
{/section}

<p>Section Loop:</p>
{section name=list loop=$form.subject}
	{section name=item loop=$list}
Subject: {$list[item]}<br>
	{/section}
{/section}

<p>HTML Options:</p>

<p>Smarty offers a simple and quick way to generate an HTML select input from an array.
</p>

<select>
{html_options options=$form.subject.options selected=$form.subject.selected}
</select><br>

<p>Or you can write your own loop:</p>

<select name="subjects">
{section name=option loop=$form.subject.options}
	<option value="{$form.subject.options[option]}" {* if selected do someting *}>{$form.subject.options[option]}<br>
{/section}
</select>

<!-- Note: in a select input, the name attribute is part of the what would normally be the "input" element in a single line input, but each possible value is contained in its own element where the value attribute is "moved" to relative to the single input type. -->

<p>Checkboxes:</p>

{section name=option loop=$form.subject.options}
	option: {$form.subject.options[option]}<br>
{/section}

{section name=item loop=$form.subject.options}
<input type="checkbox" name="toppings" value="{$form.subject.options[item]}"> {$form.subject.options[item]}<br>
{/section}

<p>HTML Radios:</p>

{html_radios options=$form.subject.options selected=$form.subject.selected}

<p>Or you can write your own loop.</p>

{section name=option loop=$form.subject.options start=1}
	<input type="radio" name="subjects" value="{$form.subject.options[option]}">{$form.subject.options[option]}<br>
{/section}


<input type="hidden" name="id" value="{$form.id}"><br>

<input type="submit" value="Edit">
</form>


<p><strong>ooForm (value):</strong></p>


<form action="index.php" method="post">
<input type="hidden" name="action" value="links/saveLink">

<input type="text" name="title" value="{$value_title}"><br>
<input type="text" name="link" value="{$value_link}"><br>
<input type="text" name="description" value="{$value_description}">

<input type="hidden" name="id" value="{$value_id}"><br>

<input type="submit" value="Edit">
</form>

<p>IMPORTANT: Smarty does NOT allow quotes around the values specified to parameters in section or foreach clauses. DO NOT WRITE: section name="item" loop="$loop.var" or it will fail.
</p>

</body>
</html>
