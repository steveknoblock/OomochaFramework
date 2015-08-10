{* debug *}

<p>Edit Profile</p>

<form action="index.php" method="post">

<input type="hidden" name="action" value="users/edit" />

<fieldset>
<legend>Name</legend>

<p>Username: <input type="text" name="username" value="{$form.username.value}" disabled="disabled" />{$form.username.error} (not editable)</p>

<p>First Name: <input type="text" name="personalname" value="{$form.personalname.value}" />{$form.personalname.error}<br></p>

<p>Last Name: <input type="text" name="familyname" value="{$form.familyname.value}" /><br></p>

</fieldset>

<fieldset>
<legend>Contact</legend>

<p>Email: <input type="text" name="email" value="{$form.email.value}" />{$form.email.error}<br></p>


</fieldset>


<fieldset>
<legend>Membership Type</legend>
<p>Are you an individual fresh food lover, or are you a farmer? Your membership will be avaiable immediately if you are an individual. Farmers must wait for approval.
</p>

{html_radios name="usertype" options=$form.usertype.options selected=$form.usertype.selected separator="<br />"}


</fieldset>

<fieldset>
<legend>About</legend>

<p>Tell the world about yourself. This will be publicly displayed.
</p>

<textarea cols="55" rows="25" name="interests">
{$form.interests.value}
</textarea>
{$form.interests.error}

</fieldset>


<fieldset>
<legend>Reset Password</legend>

<p><a href="resetpassword">Reset Password</a>
</p>


</fieldset>

<fieldset>
	<legend>
		Newsletters
	</legend>
		
{html_checkboxes name="email_options" options=$form.email_options.options selected=$form.email_options.selected separator="<br />"}

</fieldset>


<input type="hidden" name="userid" value="{$form.userid.value}" />
{$form.userid.error}
<br>

<input type="hidden" name="submitted" value="1" />
<input type="submit" value="Edit" />

</form>

