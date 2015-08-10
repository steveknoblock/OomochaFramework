<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Untitled</title>
</head>

<body>

<p><strong>Form:</strong></p>

<form action="index.php" method="post">
<!-- input type="hidden" name="action" value="links/saveLink" -->

<input type="hidden" name="action" value="links/saveLink">

Title: <input type="text" name="title" value="{$form.title.value}"><br>
Link: <input type="text" name="link" value="{$form.link.value}"><br>
Description: <input type="text" name="description" value="{$form.description.value}"><br>
<select>
{html_options options=$form.subject.options selected=$form.subject.selected}
</select><br>

<input type="hidden" name="id" value="{$form.id.value}"><br>

<input type="hidden" name="submitted" value="1">
<input type="submit" value="Edit">
</form>


</body>
</html>
