<?

/**********************************************************************
 *                          SweetPear Framework
 *                       Copyright 2006 Steve Knoblock
 *                                GPL License
 **********************************************************************/
  
/**
 * View
 * View functionality.
 * @author Steve Knoblock
 * @version 0
 * @date begin: 
 * @date revised: 
 */
 
require_once '/usr/home/cityg/tools/Smarty/Smarty.class.php';

class MVC_View {
    var $template;
    var $template_dir;

    function MVC_View() {
        $this->template = new Smarty();


        $smarty_dir = "/usr/home/cityg/web/phphelp/Smarty/"; //NO trailing slash!
        $smarty_template_dir = "$smarty_dir/templates/"; //NO trailing slash!
        $smarty_compile_dir = "$smarty_dir/templates_c"; //NO trailing slash!


        $smarty = new Smarty;
        $this->template->compile_check = TRUE;
        $this->template->template_dir = $smarty_template_dir;
        $this->template->compile_dir =  $smarty_compile_dir;

        $this->setTemplateDir();
		
		/**
		 * Make standard assignments from configuration.
		 * This is likely to be moved should a better place be found.
		 * This is really a pain to have to update this every time a
		 * new sitewide value is added to config that needs to be
		 * passed automatically through to the template engine. It
		 * should be easy to add a new piece of custom data to
		 * pass to the engine. Pull these values from the database?
		 */
		
		// site characteristics
 		$this->assign('SITE_TITLE', SITE_TITLE);
 		$this->assign('SITE_CHARSET', SITE_CHARSET);
		// framework data
 		$this->assign('WEB_ROOT', WEB_ROOT);
 		$this->assign('THEME_STYLES_URL', THEME_STYLES_URL);
 		$this->assign('THEME_IMAGES_URL', THEME_IMAGES_URL);
		// dynamic data
		// All modules on the site could benefit from having
		// the name of the currently logged in user. This needs
		// to be displayed on nearly every page in an application.
		// this depends on auth module, should auth method name
		// change, this must be changed, be nice to have a wrapper
		// object around auth
		// yep, fails, context is not available here
 		//$this->assign('AUTH_USER_NAME', $this->c->auth-getUsername());
		// the context gets passed to the model, so it needs to be passed
		// here as well
		// but for now, just pick up from front controller
		
		$this->assign('AUTH_USER_NAME', AUTH_USER_NAME);
		
    }

    function setTemplateDir($dir = "") {
        $this->template_dir = $dir;
    }

    function assign($name, $data) {
	
		/*
		 * As far as I know, Smarty does not automagically convert
		 * an associative array to placeholders, like perl's
		 * HTML::Template.
		 * For example:
		 * $bar = array( 'title' => 'quiz', 'url' => 'dotcomdtocom');
		 * $smarty->assign( 'foo', $bar);
		 * does not allow you to access these automagically like this
		 * {$title} {$url}
		 * but must be
		 * {$bar.title} ($bar.url}
		 * To do this, you would have to iterate over the array
		 * making assignments for each key and value.
		 */
		
        $this->template->assign($name, $data);
    }

    function display($template = "") {


        print $this->fetch($template);
    }

    function fetch($template = "") {
        if (empty($template)) {
            exit("No template assigned");
        }
        $this->template->caching = 0;
        return $this->template->fetch(  $this->template_dir . "/" . $template . '.tpl');
    }

}

?>