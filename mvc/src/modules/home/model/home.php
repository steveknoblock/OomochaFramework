<?

/*
 * MVC_Model class defined in front controller.
 */

		
class HomeModel extends MVC_Model {

    function HomeModel( $c ) {
	
         parent::MVC_Model( $c );
    
	}


    function getHome() {

	
		return array( 'foo' => 'bar');
    }


} // end of Model


?> 