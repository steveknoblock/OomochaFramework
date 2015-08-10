<?

/**********************************************************************
 *                          SweetPear Framework
 *                       Copyright 2006 Steve Knoblock
 *                                GPL License
 **********************************************************************/
  
/**
 * MVC Model
 * Model functionality.
 * @author Steve Knoblock
 * @version 0
 * @date begin: 
 * @date revised: 
 */
 
class MVC_Model {
    var $result;
    var $db;
	var $c;
	
    function MVC_Model( $c ) {
        // db connection here
	
		//$db =& DB::connect($dsn, $options);
	
	    $this->db = DB::connect($c->dsn);
	
		if (DB::isError($this->db)) {
	    	die($this->db->getMessage());
		}      
	        
	        $this->result = null;
			

		// Debugging		
		// if( isset($c) ) {
		// 	print "Model has the Context!";
		//	print "<pre>";
		//	print_r( $c );
		//	print "</pre>";
		//}
		
		$this->c = $c;
		
    }

    function fetchToArray() {
        $result_array = array();
        while($row = $this->result->fetchRow(DB_FETCHMODE_ASSOC) ) {
            $result_array[] = $row;
        }
        return $result_array;
    }
}

?>