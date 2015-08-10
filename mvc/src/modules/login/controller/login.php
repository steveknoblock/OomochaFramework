<?

/**
 * Login Controller
 *
 */


/**
 * Specify module actions to the context.
 * Maps virtual urls to methods.
 */
	
	
	
	$fc->add_action( 'login/login', 'login', 'Login', 0);

	$fc->add_action( 'login/logout', 'login', 'Logout', 0);
	
			
class LoginController extends MVC_Controller {

    function LoginController($c) {

        parent::MVC_Controller( $c );
		
        $this->model = new LoginModel($this->c);
		
        $this->view->setTemplateDir('login'); // FIXME: this needs automating

		log_err( __FILE__, __LINE__, "Info: About to do action: $action" );
			
        $this->doAction();
    }

    function defaultMethod() {
        $this->displayLogin();
    }


	/**
	 * Login
	 *
	 */

    function Login() {
	
	log_err( __FILE__, __LINE__, "Info: (Controller.EditForm))" );
	
	$this->c->plugins[form]->init(
		array(
			'username',
			'password',
			'rebound'
			//,'rememberlogin'
			)
		);

	$this->c->plugins[form]->params( $this->c->request->params ); 
	$this->c->plugins[form]->validate_fields( array() );
	$this->c->plugins[form]->required_fields( array() );
	$this->c->plugins[form]->templateobj->set_mod_dir( '/login/' ); // trailing slash required

/**
 * Given the quirks of Auth, the only purpose of this
 * module is to display the login form through the
 * framework template engine at a valid framework
 * url.
 * Although, normally, one might expect the this method
 * to check the authenitcations staus, it can't. Ideally,
 * the framwork would contain an auth object, which the
 * method could interrogate to see if the login is
 * authentic. But this is done automatically in Auth
 * before it ever gets here and is beyond my control.
 */
 

	if( $this->c->plugins[form]->submitted() ) {
	
	// debug
	// print "<p>Submitted</p>";
	
	/*
	 * IMPORTANT: Normally, a check would be done on login
	 * form submission to see if the user is authentic, but
	 * the PEAR Auth module does this automatically and
	 * opaquely at $auth->start invocation. So I have no
	 * control over this process, not even the names of
	 * form inputs. If this point is reached, the user will
	 * either be logged in or not logged in. If they are
	 * logged in then we can safely send them to their
	 * destination, otherwise display the login page.
	 * I don't know how to just block this in the front
	 * controller on login form submission. Every form
	 * in the framework should be handled by a module at
	 * a framework url and use the template engine and
	 * I would hope they use the built-in form handler
	 * although that is optional.
	 */
			
	/**
	 * Redirect to the location supplied
	 * by the form. It should redirect the browser back to
	 * the original page that prompted the login.
	 * A "forward" could work here.
	 */
	 
	if( $this->c->auth->getAuth() ) {
	
	
		// find out what the requested location is
		
		// debug
		// print "Rebounding To: ". $this->c->request->params['rebound'];
		// print "Location: http://". DOMAIN .'/'. WEB_ROOT .'/index.php?action='. $this->c->request->params['rebound'];

		if( empty( $this->c->request->params['rebound'] ) ) {
			// FIXME:
			// set default location
			// default default would be homepage
		}
	
		header('Location: http://'. WEB_ROOT .'/index.php?action='. $this->c->request->params['rebound']);
		
		
		exit;
		
		// debug
		// print "<strong>Authenticated</strong>";
		
	} else {
				
		// debug
		// print "<strong>Not Authenticated</strong>";	
		
		$form_html = $this->c->plugins[form]->render('mvc-login-form.tpl', FORM_NORMAL);
		$this->view->assign("form_view",  $form_html );
  
  		/**
		 * Note: A single login message is used to inform the
		 * user of a failed login. This avoids displaying error
		 * messages by the username and password fields, which
		 * hides any clues those might give to hackers as to
		 * which field failed. Also, it is not really known at
		 * this point which one failed anyway...this is not
		 * field validation failing but login failing.
		 */
		define('FAILED_LOGIN_MSG', "Your login information is incorrect, please try again.");
		$this->view->assign("login_message", FAILED_LOGIN_MSG );
		$this->view->display('mvc-login');
		
	}
				
			
	} else {
	
		// debug
		print "<p>Not Submitted</p>";
		
		/**
		 * Here the framework must discover what url
		 * the user was originally requesting.
		 * so if they wanted links/list it must
		 * encode this on the login form
		 */


		 // assign to form template
		
		// debug
		print "Assigning Rebound: ". $this->c->request->params['rebound'];
		$this->c->plugins[form]->field('rebound', $this->c->request->params['rebound']);
		 		
		$form_html = $this->c->plugins[form]->render('mvc-login-form.tpl', FORM_NORMAL);
		$this->view->assign("form_view",  $form_html );
        $this->view->display('mvc-login');
		
	}
		
		

    }


	
		
}


?>