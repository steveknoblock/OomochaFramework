<html>
<head>
<title>List of Links</title>
</head>
<body>
<h3>List of Links</h3>

<form action="index.php" method="post">
<input type="hidden" name="action" value="links/add">
<input type="submit" value="Add a Link">
</form>
{section name="i" loop=$linkData }
<a href="{$linkData[i].link}">{$linkData[i].title}</a>
<p>{$linkData[i].description}</p>
<hr>
{/section}

</body>
</html>