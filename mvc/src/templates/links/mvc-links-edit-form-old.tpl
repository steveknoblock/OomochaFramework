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

<select>
{html_options options=$form.subject.options selected=$form.subject.selected}
</select><br>

{html_checkboxes name="subjects" values=$form.subject.options output=$form.subject.options selected=$form.subject.selected separator="<br />"}

{html_radios options=$form.subject.options selected=$form.subject.selected}

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
