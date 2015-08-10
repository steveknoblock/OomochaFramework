
<!-- begin form -->
<form action="index.php" method="post">

<!-- the value for action should be automatically generated? but then that might prevent defining the value in actions? unless you take it from the save action? maybe best to require manual specification -->
<input type="hidden" name="action" value="users/register">

<h3>Registration</h3>

<fieldset>
	<legend>
		User Information
	</legend>
	
	<p>Please choose a username to identify you on the site.
	</p>
	
<p>Username: <input type="text" name="username" value="{$form.username.value}">*</p>

<p>Password: <input type="password" name="password" value="{$form.password.value}">*</p>

<p>Confirm Password: <input type="password" name="confirmpass" value="{$form.confirmpass.value}">* (Please type your password again)</p>

<p>Your email address will NEVER be sold, rented or given to a third party or displayed publicly on this website.
</p>

<p>Email: <input type="text" name="email" value="{$form.email.value}">*</p>

<p>First Name: <input type="text" name="personalname" value="{$form.personalname.value}">*</p>

<p>Last Name: <input type="text" name="familyname" value="{$form.familyname.value}">*</p>

<p>* is a required field</p>

</fieldset>



<fieldset>
	<legend>
		Compliance Information
	</legend>
	
	<p>We require that members of our site be age eighteen or older in order to ensure that children do not use the site without their parent's supervision. We take our responsiblity as a social networking site seriously. Your date of birth will never be intentionally displayed to the public on our website. You agree your date of birth may be used to create non-personally identifying statistical data on the demographics of our visitors for use in marketing.
	</p>

	Birth Date (mm/dd/yyyy):
	<input type="text" size="2" name="month" value="{$form.month.value}">&nbsp;/&nbsp;
	<input type="text" size="2" name="day" value="{$form.day.value}">&nbsp;/&nbsp;
	<input type="text" size="4" name="year" value="{$form.year.value}">
	

</fieldset>

<fieldset>
<legend>Membership Type</legend>
<p>Are you an individual fresh food lover, or are you a farmer? Your membership will be avaiable immediately if you are an individual. Farmers must wait for approval.
</p>

{html_radios name="usertype" options=$form.usertype.options selected=$form.usertype.selected separator="<br />"}


</fieldset>

<fieldset>
	<legend>
		Description/Interests
	</legend>
	
	<p>Tell the world about yourself and your interests. Please remember the information you enter here will appear to the public on your member profile. You are not required to enter interests.
	</p>

	<p>You may use &lt;p&gt; to make paragraphs.
	</p>
	
<textarea cols="32" rows="15" name="interests">{$form.interests.value}</textarea>

</fieldset>

<fieldset>
	<legend>
		Newsletters
	</legend>
	
	<p>We will NEVER sell, rent or give your email address to any third party. You may choose to opt-in to recieve occasional announcments (such as security messages or updates to site feature) or to recieve a regular (planned for quarterly) newsletter featuring a variety of information and special offers. You are not required to subscribe to either mailing list.
	</p>
	
{html_checkboxes name="email_options" options=$form.email_options.options selected=$form.email_options.selected separator="<br />"}

</fieldset>

<input type="hidden" name="submitted" value="1">
<p><input type="submit" value="Join!"></p>
</form>
<!-- end form -->
