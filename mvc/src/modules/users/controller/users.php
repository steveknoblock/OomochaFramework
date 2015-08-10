<?

/**
 * Users Controller
 *
 */

  
/**
 * Helpers
 */  
 
 /**
  * LF Unix
  * CR+LF DOS/Windows
  * CR Apple
  */
 //."\r\n";
  
function normalize_text($text) {
	$text = ereg_replace("\r\n","\n",$text); // PC to Unix MUST come first!
	$text = ereg_replace("\r","\n",$text); // Mac to Unix
	return $text;
}



//------------------------------------------------------------------
// send_confirm_passwordreset: send new password to user

function send_confirm_passwordreset( $email_address, $confirmcode ) {
	
	// Some should be in configuration
	$subject = SITE_TITLE . ' Reset Password';
	// Format using sprintf() special characters (%s for string)
	$body = "Someone has requested the password for the following account be reset:

Username: 

To reset your password go to the following location, otherwise you may ignore this message.

http://%s/index.php?action=users/resetpassword&confirm=%s


Thank you,

%s Community

";

// debug
// print "About to send mail: ";
// print "<p>To: $email_address</p>";
// print "<p>From: ". ADMIN_EMAIL ."</p>";
// print "<p>Subject: $subject</p>";
// print "<p>Confirmation: $confirmcode</p>";

	mail($email_address, $subject, sprintf($body, WEB_ROOT, $confirmcode, SITE_TITLE), "From: ". ADMIN_EMAIL ."\r\n");
}


function send_password( $email_address, $password ) {
	
	// Some should be in configuration
	$subject = SITE_TITLE . ' New Password';
	// Format using sprintf() special characters (%s for string)
	$body = "Your new password:

Password: %s

Thank you,

%s Community

";

// debug
// print "About to send mail: ";
// print "<p>To: $email_address</p>";
// print "<p>From: ". ADMIN_EMAIL ."</p>";
// print "<p>Subject: $subject</p>";
// print "<p>Confirmation: $confirmcode</p>";

	mail($email_address, $subject, sprintf($body, $password, SITE_TITLE), "From: ". ADMIN_EMAIL ."\r\n");
}


/**
 * Specify module actions to the context.
 * Maps virtual urls to methods.
 */

	$fc->add_action( 'users/register', 'users', 'AddForm', 0);

	$fc->add_action( 'users/view', 'users', 'View', 0);
		
	$fc->add_action( 'users/edit', 'users', 'EditForm', 1);
	
	$fc->add_action( 'users/resetpassword', 'users', 'ResetPasswordForm', 0);
	
	// this is an admin function
	//$fc->add_action( 'users/delete', 'users', 'showDeleteForm', 1);
	//$fc->add_action( 'users/list', 'users', 'Browse', 0);	
			
			
class UsersController extends MVC_Controller {

    function UsersController($c) {


        parent::MVC_Controller( $c );
		
        $this->model = new UsersModel($this->c);
		
        $this->view->setTemplateDir('users'); // FIXME: this needs automating

		// this should go into debug log, not event log
		//log_err( __FILE__, __LINE__, "Info: About to do action: $action" );
			
        $this->doAction();
    }

    function defaultMethod() {
        $this->displayUsers();
    }


	/**
	 * List/Browse
	 *
	 */
	
    function Browse() {

	// FIXME: username or cgi environment
		$this->model->pkey = $this->c->auth->getUsername();
				
        $linkData = $this->model->getUsers();
        $this->view->assign('linkData', $linkData);
        $this->view->display('mvc-links');
    }
	
	
	/**
	 * Create/Add
	 *
	 */

    function AddForm() {

	log_err( __FILE__, __LINE__, "Requesting new user registration." );
	
	//print "Entering AddForm";
	
	$this->c->plugins[form]->init(
		array(
		//'userid', NOT USEFUL TO CREATE
		'username',
		'password',
		'confirmpass',
		//'usergroup', // What about user roles? Or is the role define by the group they belong to? Farmers is group, but Editor is a role on the site? Roles refer to what your role on the site is, the group, the users who share certain qualities.
		'email',
		'personalname',
		'familyname',
		'month',
		'day',
		'year',
		'interests',
		'usertype',
		'email_options'
			)
	);
 
	$this->c->plugins[form]->params( $this->c->request->params ); 

	/**
	 * '/^.+\@([a-z0-9]+(-[a-z0-9]+)?\.)+([a-z]{2,3})$/'
	 */
	$this->c->plugins[form]->validate_fields( array() );
	
	$this->c->plugins[form]->required_fields( array(
		'username',
		'password',
		'confirmpass',
		'email',
		'personalname',
		'familyname',
		'month',
		'day',
		'year'	
	) );
	
	/**
	 * This tells the form adaptor to look in template folder associated
	 * with the module. Could be automated. Unnecessary if all templates
	 * in one folder.
	 */
	$this->c->plugins[form]->templateobj->set_mod_dir( '/users/' ); // trailing slash
			
		if( $this->c->plugins[form]->submitted() ) {
	
			if($this->c->plugins[form]->validate() ) {	
	
	
	/**
	 * Check age and other contraints.
	 *
	 */
	 
	 /*
	  * FIXME: these should look to form fields, not the params.
	  */
	 
	 //print $this->c->plugins['datecalc']->date_to_age(26, 5, 1964);
	  	 
	$age = $this->c->plugins['datecalc']->date_to_age($this->c->request->params['day'], $this->c->request->params['month'], $this->c->request->params['year']);
 	if($age < AGE_LIMIT ) {
	
		die("You you must be age eighteen or older to join.");
	 }
	
	if( $this->c->request->params['password'] != $this->c->request->params['confirmpass'] ) {

	die("Your password does not match the confirm password. Please check your password.");
}
				$this->model->pkey = ''; // NULL primary key forces insert on save
				
				$this->model->saveUser();

				/**
				 * Thank You
				 * This needs to display or redirect to a thank you page, or to some appropriate page.
				*/
				//$this->forward( 'list' );
				
				print "Thanks";
				
				$this->view->display('mvc-new-user-response');
				
				
			} else {
			
		// Farm/Foodie type radio buttons
		$data = array(1=>'Farmer/Producer',2=>'Foodie/Consumer');
		$this->c->plugins[form]->options('usertype', $data);
		// probably a good idea to set the default to consumer
		// This may need some special second form.
		$this->c->plugins[form]->field('usertype', 2);
		
		// FIXME: THIS HAS BEEN CHANGED FROM TWO FIELDS TO ONE!
		$data = array(1=>'Newsletter',2=>'Announcements');
		$this->c->plugins[form]->options('email_options', $data);
		
			$form_html = $this->c->plugins[form]->render('mvc-users-add-form.tpl',1);
		
		$this->view->assign("form_view",  $form_html );

        $this->view->display('mvc-users-add');
		
			}
	} else {
	
	
		// Farm/Foodie type radio buttons
		$data = array(1=>'Farmer/Producer',2=>'Foodie/Consumer');
		$this->c->plugins[form]->options('usertype', $data);
		// probably a good idea to set the default to consumer
		// This may need some special second form.
		$this->c->plugins[form]->field('usertype', 2);
		
		// FIXME: THIS HAS BEEN CHANGED FROM TWO FIELDS TO ONE!
		$data = array(1=>'Newsletter',2=>'Announcements');
		$this->c->plugins[form]->options('email_options', $data);
		
		
				$form_html = $this->c->plugins[form]->render('mvc-users-add-form.tpl',0);
		
		$this->view->assign("form_view",  $form_html );

        $this->view->display('mvc-users-add');
			
		
	}
	
		
    }
	
	/**
	 * Retrieve/View
	 *
	 */

	function View() {
		
		// debug
		//print "View";
		
		log_err( __FILE__, __LINE__, "Info: (MVC_Controller.showView))" );
		
		// FIXME: username or from cgi environment
		$this->model->pkey = $this->c->auth->getUsername();
		$userData = $this->model->getUser();
        
		//print_r( $linkData );
		$this->view->assign('grid', $userData);
        $this->view->display('mvc-users-view');
		
	}
		
	
	/**
	 * Edit/Update
	 *
	 */
	 
    function EditForm() {
	
	// debug
	// print "Edit Form";
	
	//log_err( __FILE__, __LINE__, "Info: (Controller.EditForm))" );
	
	$this->c->plugins[form]->init(
		array(
		'userid', // Essential for saving data, the key field
		'username',
		'password',
		'confirmpass',
		'email',
		'personalname',
		'familyname',
		'interests',
		'email_options',
		'subject',
		'usertype'
			)
		);

	$this->c->plugins[form]->params( $this->c->request->params ); 
	$this->c->plugins[form]->validate_fields( array() );
	$this->c->plugins[form]->required_fields( 
		array(
		/**
		'username',
		'password',
		'email',
		'personalname',
		'familyname',
		'interests'
		*/
			)
		);
	$this->c->plugins[form]->templateobj->set_mod_dir( '/users/' ); // trailing slash required

	if( $this->c->plugins[form]->submitted() ) {
	
		if($this->c->plugins[form]->validate() ) {
				
			print "<strong>Validated</strong>";
			
			// FIXME: username or cgi environment
			$this->model->pkey = $this->c->auth->getUsername();				
			$this->model->saveUser();
			
			// it should display the user profile in response to a succesful update
			//$this->forward( 'list' );
		
		print "Successful update";
		
		} else {
		
			print "<strong>Not Validated</strong>";				
		
		/** hack in subjects
		
		$data = array(1=>'Food',2=>'Music',3=>'Women',4=>'Agriculture',5=>'Regional');
		// singular! The name of the field should be singular, it is the
		// _subject_ of the link, not the _subjects_ of the link. Unless
		// multi-select.
		$this->c->plugins[form]->options('subject', $data);
		$this->c->plugins[form]->field('subject', 2);
		*/
		/**
		typcial usage
	$form->options("streamformat", $data);
	$form->field('streamformat', $row2['streamformat']);
	*/
	
		// FIXME: THIS HAS BEEN CHANGED FROM TWO FIELDS TO ONE!
		$data = array(1=>'Newsletter',2=>'Announcements');
		$this->c->plugins[form]->options('email_options', $data);
		
	$form_html = $this->c->plugins[form]->render('mvc-users-edit-form.tpl', FORM_STICKY);
			
		$this->view->assign("form_view",  $form_html );

        $this->view->display('mvc-users-edit');
		}
	} else {
		
		//print "<strong>Editing</strong>";
		
		// the prerendered form is output to a single placeholder here
        // unnecessary to assign form fields
		// perhaps some method of including the form template
		// and then assigning the form values to template placholders
		// done through the framework's template engine
		//
		// get the form handler integrated into the framework
		// how to get the data from the model into the form?
		
		// okay, what I need to do as a temporary solution is
		// assign the model data to the form handler object
		// manually
		
		// FIXME: username or cgi environment
		$this->model->pkey = $this->c->auth->getUsername();
		$profile = $this->model->getUser();


		// Debugging
		// print "<pre>Profile:\n";
		// print_r( $profile );
		// print "</pre>";

		
		/**
		 Note: Here is the idea. Treat checkboxes always as a group, like a radio group or a select menu with multiple selected values. A radio group and a select menu are similar in strucutre. They both are a list of options with either one (radio) or potentially multiple (select) selected values. A single checkbox can be regarded as a single item select menu. A multiple checkbox group can be regarded as a select list with the potential for selecting multiple values.
	This means checkboxes should be setup and handled as select menus. They would no longer be a single valued value but an array value. It is not that much effort to get the values from the array returned by the form into the corresponding database columns. Not any more than handling a multiple select input.
		
	This method requires setting up options for each checkbox group.
		
	It allows checkboxes that might be single to be handled as a group, such as the opt in mailing list checkboxes.

Hmm, the only flaw in this is that there is not a single options list. Let me explain. A select menu might be called Toppings and contain Mushrooms, Pepperoni, Onion. A user can select one value or more than one value, i.e. Mushrooms and Pepperoni.

Now, what about a checkbox group?

Suppose we want to ask the user what email they wish to subscribe to? A Newsletter or Announcements.

This presents a two valued list, were it a select menu. A single select menu could offer a list showing the two items. The user could select one or both.

Well, no, maybe there is no problem with this. The html_checkboxes is generating a nice list of checkboxes and this is how you feed the thing. It's working.

<option label="Music" value="2" selected="selected">Music</option>

<input type="radio" name="radio" value="2" checked="checked" />Music

The 'selected' value is set to 2 and matches the value in the associative array of options given to Smarty. It gets marked selected or checked where 2=2.

So apparently what is happening with the checkbox group is that it wants to match on "Music" instead of 2. The strange thing is this contradicts the Smarty manual. Yes, changing the selected value to 'Music' rendered checked.

<input type="checkbox" name="subject[]" value="Music" checked="checked" />Music

I think the problem is that the selected values are being drawn from the value side of the options associative array. This may not be correct for options and radios, but it may be. But the problem is the checkboxes require the index, so you must supply 2

for 2 => 'Music'

but apparently, I am not feeding the index value but the label value into the selected array.

Okay, no the problem was not any of this. The problem was that I had specified
{html_checkboxes name="subject" values=$form.subject.options output=$form.subject.options selected=$form.subject.selected separator="<br />"}
isntead of
{html_checkboxes name="subject" options=$form.subject.options selected=$form.subject.selected separator="<br />"}

The handling of these two forms is different. My form handler expects the options form.

		*/
		
		foreach( $profile as $k=>$v ) {
			$this->c->plugins[form]->field($k, $v);
		}
		
		// Farm/Foodie type radio buttons
		$data = array(1=>'Farmer/Producer',2=>'Foodie/Consumer');
		$this->c->plugins[form]->options('usertype', $data);
		$this->c->plugins[form]->field('usertype', 2);
		
		
		$data = array(1=>'Food',2=>'Music',3=>'Women',4=>'Agriculture',5=>'Regional');
		// singular! The name of the field should be singular, it is the
		// _subject_ of the link, not the _subjects_ of the link. Unless
		// multi-select.
		$this->c->plugins[form]->options('subject', $data);
		$this->c->plugins[form]->field('subject', 2);
		
		$data = array(1=>'Newsletter',2=>'Announcements');
		$this->c->plugins[form]->options('email_options', $data);
		
		// process email_options
		// translates from two columns with binary values to a list of values
		if( $profile['optin_newsletter'] ) {
			$tmp[] = 1;
		}
		if( $profile['optin_announce'] ) {
			$tmp[] = 2;
		}
				
		// debug
		// takes an array
		// print_r( $tmp );
		
		$this->c->plugins[form]->field('email_options', $tmp);
			
		/**
		typcial usage
	$form->options("streamformat", $data);
	$form->field('streamformat', $row2['streamformat']);
	*/
	
	$form_html = $this->c->plugins[form]->render('mvc-users-edit-form.tpl', FORM_NORMAL);
			
		$this->view->assign("form_view",  $form_html );

        $this->view->display('mvc-users-edit');
		
	}
		
		

    }

	/**
	 Note: This is a problem I have encountered before using CFB to handle forms
	 when the interaction begins with a form but ends with a GET mode request,
	 which is being handled by a single block of code. I've handled this before
	 by specifying 'submitted' parameter in the GET url, but it is ugly,
	 although I've seen it on other websites. It seems the better solution is
	 to create separate urls for each function:
	 
	 users/resetpassword
	 
	 users/confirmresetpassword
	 
	 or something shorter like
	 
	 users/newpassword
	 
	 perhaps 
	 
	 users/requestpassword
	 users/resetpassword
	 
	 although it is elegant in another way to determine the nature of the request based on the existience of the token, so that the url is uniformly

	users/resetpassword
	
	for displaying the form and for responding to the form submission and for "picking up" the new password, which is really responding to the confirmation link in the email (GET request).
	
	Perhaps I could move this response outside of the "submitted" code, since it is a get request?

	 */
	
	/**
	 * Reset Password
	 *
	 */
	 	 
    function ResetPasswordForm() {
	
	log_err( __FILE__, __LINE__, "Request to reset password made." );
	
	$this->c->plugins[form]->init(
		array(
		'username',
		'email'
			)
		);

	$this->c->plugins[form]->params( $this->c->request->params ); 
	$this->c->plugins[form]->validate_fields( array() );
	$this->c->plugins[form]->required_fields( 
		array(
		'username',
		'email'
			)
		);
	$this->c->plugins[form]->templateobj->set_mod_dir( '/users/' ); // trailing slash required

	if( $this->c->plugins[form]->submitted() ) {
	
		if($this->c->plugins[form]->validate() ) {
			
			// start valid
			// debug
			// print "<strong>Validated</strong>";				
					
			
			/**
			 * Note: it is obvious, I hope, that the user should not need to
			 * be logged in when requesting this, otherwise they would not
			 * be in need of finding a password (unless an anonymous or guest
			 * user has a session). This means we don't know their
			 * user profile.
			 */
			
		$email = $this->c->request->params['email'];
		
		// debug
		//print "Email: $email";
		
		// confirmation code is md5 of email, 
		// gives an opportunity to compare the input
		// against the real email address
		$confirmcode = md5($this->c->request->params['email']);
		
		$this->model->setConfirm($this->c->request->params['username'], $confirmcode);
		
		send_confirm_passwordreset( $email, $confirmcode );

		print "Password reset confirmation sent";	
		
		//log_err( __FILE__, __LINE__, "Successful password reset." );
			
		} else {
		
			print "<strong>Not Validated</strong>";				
		
			$form_html = $this->c->plugins[form]->render('mvc-users-resetpassword-form.tpl', FORM_STICKY);
			
		$this->view->assign("form_view",  $form_html );

        $this->view->display('mvc-users-resetpassword');
		
				
		}
		
		} else {

			if( empty($this->c->request->params['confirm'] ) ) {
				// ask for password to be reset
				$form_html = $this->c->plugins[form]->render('mvc-users-resetpassword-form.tpl', FORM_NORMAL);
				$this->view->assign("form_view",  $form_html );
				$this->view->display('mvc-users-resetpassword');
			} else {

				/**
				 * This is a tricky sequence. The raw password must be
				 * sent to the user, but the encrypted password is stored
				 * in the database.
				 */
				 
				// confirming and reset password
				$newpass = $this->model->setPassword($this->c->request->params['confirm']);

				$profile = $this->model->getUserByConfirm( $this->c->request->params['confirm'] );
				
				print_r( $profile );
				
				// Why isn't the profile an object?
				// Damn, the password is not returned in the typical profile
				// as a security measure. But to send a new password, it
				// must retrieve it.
				send_password( $profile['email'], $newpass );
										
				$this->view->display('mvc-users-passwordsent');
				
			}
				
		}

    }  // end function
		
} // end class

?>