
<!-- begin form -->
<form action="index.php" method="post">

<input type="hidden" name="action" value="users/resetpassword">

<h3>Reset Password</h3>

<fieldset>
	<legend>
		User Information
	</legend>
	
	<p>Submitting this form sends a new password to your email address. The email address and username you submit must match those you provided when you signed up for the account.
	</p>
	
<p>Username: <input type="text" name="username" value="{$form.username.value}">*</p>

<p>Email: <input type="text" name="email" value="{$form.email.value}">*</p>


<p>* is a required field</p>

</fieldset>

<input type="hidden" name="submitted" value="1">
<p><input type="submit" value="Reset Password"></p>
</form>
<!-- end form -->
