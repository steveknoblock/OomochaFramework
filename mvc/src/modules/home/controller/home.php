<?

/**
 * Links Controller
 *
 */

  
/**
 * Specify module actions to the front controller.
 * Maps virtual urls to methods.
 */
 

	// module/action module_name method_name auth/no auth
	$fc->add_action( 'home/home', 'home', 'Home', 0 );
			
		
class HomeController extends MVC_Controller {

    function HomeController( $c ) {

        parent::MVC_Controller( $c );
			
		
        $this->model = new HomeModel($this->c);
		
        $this->view->setTemplateDir('home'); // FIXME: this needs automating

		log_err( __FILE__, __LINE__, "Info: About to do action: $action" );
			
        $this->doAction();
    }

    function defaultMethod() {
        $this->Browse();
    }


	/**
	 * List/Browse
	 *
	 */
	
    function Home() {
	

// grab username
		$username = $this->c->auth->getUsername();
		//print "Username: $username";
 
  // print "UserID: $userid";
$linkData = $this->model->getHome();
        $this->view->assign('linkData', $linkData);

        $this->view->display('mvc-home');

/**
 * This is tricky. I can't force the Auth object to display the form
 * it knows about here, so I have to display something to a user when
 * they are told 'no access' which ought to be a login form.
 */

        // could redirect to /login/login&login=1
		
    }
	}


?>