<?

/*
 * MVC_Model class defined in front controller.
 */

		
class LoginModel extends MVC_Model {

    function LoginModel( $c ) {
	
         parent::MVC_Model( $c );
    
	}

    function getLogin() {

		log_err( __FILE__, __LINE__, "(MVC_Model.getlink())" );
	
	/*
	FIXME:
	The db code needs changing to
	
		$db->setFetchMode( DB_FETCHMODE_ASSOC );
    $res =& $db->query($sql);
    if ( DB::isError( $res ) ) {
        print "Error - could not select: ";
        die( $res->getMessage() );
    }
	while( $row =& $res->fetchRow() )
	
	*/
	
	
	//print "<p>Model: params</p>";
	//print_r($this->c->request->params);
	
        //$linkKey = $_REQUEST['id'];
		$linkKey = $this->c->request->params['id'];
		
		log_err( __FILE__, __LINE__, "Link Key: $linkKey" );
		
        $sql = "SELECT * FROM links WHERE id=".$this->db->quote($linkKey);
        $this->result = $this->db->query($sql);


		//$tmp = $this->result->fetchRow(DB_FETCHMODE_ASSOC);
		// debug
		//print "<pre>";
//print_r( $tmp );
//print "</pre>";
        //return $tmp;
		return $this->result->fetchRow(DB_FETCHMODE_ASSOC);
    }

    function saveLink() {
print "Creating";
        $link_id = $_REQUEST['id'];
        if ($link_id == null) {
       // print "Inserting";
	   // FIXME: param values must come from context
	   
	   
            $sql = "INSERT INTO links SET link=".$this->db->quote($this->c->request->params['link']).
                                        ", title=".$this->db->quote($this->c->request->params['title']).
                                        ", description=".$this->db->quote($this->c->request->params['description']);

        } else {
        print "Updating";
	   /*
             $sql = "UPDATE links SET link=". $this->db->quote($_POST['link']) .
                                   ", title=".$this->db->quote($_POST['title']).
                                    ", description=".$this->db->quote($_POST['description']) .
                                    " WHERE id=".$this->db->quote($_POST['id']);
        */
// Debugging
// print "<pre>Model Params:";
// print_r( $_REQUEST );
// print "</pre>";
// print "<pre>Model Params:";
// print_r( $this->c->request->params );
// print "</pre>";

		$sql = "UPDATE links SET link=". $this->db->quote($this->c->request->params['link']) .
                                   ", title=".$this->db->quote($this->c->request->params['title']).
                                    ", description=".$this->db->quote($this->c->request->params['description']) .
                                    " WHERE id=".$this->db->quote($this->c->request->params['id']);
				print $sql;					
		}

        $this->db->query($sql);

        
                 
if (DB::isError($this->db)) {
    die($this->db->getMessage());
}      


    }



} // end of ListModel


?>