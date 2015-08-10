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
	$fc->add_action( 'links/list', 'links', 'Browse', 1 );
		
	// CRUD
	// CRUD is actually applies to the model, not really
	// the controller, which should be defined in terms
	// of requests from the user and presentations back
	// to the user, not CRUD but those basic functions
	// are reflected in the workflow
	$fc->add_action( 'links/add', 'links', 'AddForm', 0);

	$fc->add_action( 'links/view', 'links', 'View', 0);
		
	$fc->add_action( 'links/edit', 'links', 'EditForm', 1);
	
	$fc->add_action( 'links/delete', 'links', 'showDeleteForm', 1);
	
		
class LinksController extends MVC_Controller {

    function LinksController( $c ) {

        parent::MVC_Controller( $c );
			
		
        $this->model = new LinksModel($this->c);
		
        $this->view->setTemplateDir('links'); // FIXME: this needs automating

		log_err( __FILE__, __LINE__, "Info: About to do action: $action" );
			
        $this->doAction();
    }

    function defaultMethod() {
        $this->displayLinks();
    }


	/**
	 * List/Browse
	 *
	 */
	
    function Browse() {
	
	print "In List Browse";
	
// grab username
		$username = $this->c->auth->getUsername();
		//print "Username: $username";
 
// print "UserID: $userid";
$linkData = $this->model->getLinks();
        $this->view->assign('linkData', $linkData);
        $this->view->display('mvc-links');

/**
 * This is tricky. I can't force the Auth object to display the form
 * it knows about here, so I have to display something to a user when
 * they are told 'no access' which ought to be a login form.
 */

        // could redirect to /login/login&login=1
		
    }
	
	
	/**
	 * Create/Add
	 *
	 */

    function AddForm() {

	log_err( __FILE__, __LINE__, "Info: (Controller.AddForm))" );
	
	$this->c->plugins[form]->init(
		array(
			'id',
			'title',
			'link',
			'description',
			'subject'
			)
	);
 
	$this->c->plugins[form]->params( $this->c->request->params ); 

	$this->c->plugins[form]->validate_fields( array() );
	
	$this->c->plugins[form]->required_fields( array() );
	
	/**
	 * This tells the form adaptor to look in template folder associated
	 * with the module. Could be automated. Unnecessary if all templates
	 * in one folder.
	 */
	$this->c->plugins[form]->templateobj->set_mod_dir( '/links/' ); // trailing slash
			
		$form_html = $this->c->plugins[form]->render('mvc-links-add-form.tpl',0);
		
		$this->view->assign("form_view",  $form_html );

        $this->view->display('mvc-links-add');
    }
	
	/**
	 * Retrieve/View
	 *
	 */

	function View() {
		print "View";
		
		log_err( __FILE__, __LINE__, "Info: (MVC_Controller.showView))" );
		
		$linkData = $this->model->getLinks();
        $this->view->assign('grid', $linkData);
        $this->view->display('mvc-links-view');
		
	}
		
	
	/**
	 * Edit/Update
	 *
	 */
	 
    function EditForm() {
	
	print "In Edit";
	
	log_err( __FILE__, __LINE__, "Info: (Controller.EditForm))" );
	
	$this->c->plugins[form]->init(
		array(
			'id',
			'title',
			'link',
			'description',
			'subject'
			)
		);

	$this->c->plugins[form]->params( $this->c->request->params ); 
	$this->c->plugins[form]->validate_fields( array() );
	$this->c->plugins[form]->required_fields( 
		array(
			'title',
			'link',
			'description',
			//'subject'
			)
		);
	$this->c->plugins[form]->templateobj->set_mod_dir( '/links/' ); // trailing slash required

	if( $this->c->plugins[form]->submitted() ) {
	
		if($this->c->plugins[form]->validate() ) {		
			print "<strong>Validated</strong>";				
			$this->model->saveLink();
			//$this->forward( 'list' );
		} else {
		
		// hack in subjects
		
		$data = array(1=>'Food',2=>'Music',3=>'Women',4=>'Agriculture',5=>'Regional');
		// singular! The name of the field should be singular, it is the
		// _subject_ of the link, not the _subjects_ of the link. Unless
		// multi-select.
		$this->c->plugins[form]->options('subject', $data);
		$this->c->plugins[form]->field('subject', 2);
		/**
		typcial usage
	$form->options("streamformat", $data);
	$form->field('streamformat', $row2['streamformat']);
	*/
	
	$form_html = $this->c->plugins[form]->render('mvc-links-edit-form.tpl', FORM_STICKY);
			
		$this->view->assign("form_view",  $form_html );

        $this->view->display('mvc-links-edit');
		}
	} else {
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
		
		$tmp = $this->model->getLink();

		// Debugging
		// print "<pre>Model:";
		// print_r( $tmp );
		// print "</pre>";

		foreach( $tmp as $k=>$v ) {
			$this->c->plugins[form]->field($k, $v);
		}
		// hack in subjects
		
		$data = array(1=>'Food',2=>'Music',3=>'Women',4=>'Agriculture',5=>'Regional');
		// singular! The name of the field should be singular, it is the
		// _subject_ of the link, not the _subjects_ of the link. Unless
		// multi-select.
		$this->c->plugins[form]->options('subject', $data);
		$this->c->plugins[form]->field('subject', 2);
		/**
		typcial usage
	$form->options("streamformat", $data);
	$form->field('streamformat', $row2['streamformat']);
	*/
	
	// need constant for FORM_STICKY = 1
	$form_html = $this->c->plugins[form]->render('mvc-links-edit-form.tpl', FORM_NORMAL);
			
		$this->view->assign("form_view",  $form_html );

        $this->view->display('mvc-links-edit');
		
	}
		
		

    }


	
    function saveLink() {
	
	/**
	 An object should know how to save itself, but the save should not be dealing with the form, other than to get its values from it indirectly through updating the object and the saving the object properties.
	This means the model must create an object with properties...although you can get away with just accessing the database using a db object and never givin the model any other property. Maybe the model should have the form and db objects?
	 */
	  
    }

		
}


?>