{* debug *}

<p>Edit Profile</p>

<p><strong>Form:</strong></p>

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
<legend>About</legend>

<p>Tell the world about yourself. This will be publicly displayed.
</p>

<textarea cols="55" rows="25" name="interests">
{$form.interests.value}
</textarea>
{$form.interests.error}

</fieldset>

<fieldset>
<legend>Update Password</legend>

<p>Enter a password twice to change your password, otherwise leave blank.
</p>

<p>Password: <input type="password" name="password" value="" />{$form.password.error}<br></p>
<p>Confirm Password: <input type="password" name="confirmpass" value="" />{$form.confirmpass.error}<br></p>

</fieldset>

<fieldset>
<legend>Subscriptions</legend>

<p><input type="checkbox" name="optin_announce" value="{$form.optin_announce.value}" checked="{$form.optin_announce.selected}" />Recieve infrequent announcements</p>

<p><input type="checkbox" name="optin_newsletter" value="{$form.optin_newsletter.value}" checked="{$form.optin_newsletter.selected}" />Recive our newsletter<br></p>

</fieldset>


<input type="hidden" name="userid" value="{$form.userid.value}" />
{$form.userid.error}
<br>

<input type="hidden" name="submitted" value="1" />
<input type="submit" value="Edit" />

Newsletter:

(The options array is empty currently).

{html_checkboxes name="subject" options=$form.optin_newsletter.options selected=$form.optin_newsletter.selected separator="<br />"}


<select>
{html_options options=$form.subject.options selected=$form.subject.selected}
</select><br>

{html_checkboxes name="subject" options=$form.subject.options selected=$form.subject.selected separator="<br />"}

{$form.subject.selected}

{html_radios options=$form.subject.options selected=$form.subject.selected}


</form>

