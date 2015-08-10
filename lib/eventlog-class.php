<?php

// could this be a regular module without URL access?
// then it could use the controller's DB connect

require_once 'DB.php';
				
class EventLog {
	var $error_level = 0; // disabled by default
	
	function event( $type, $file, $line, $message )
	{
		if( $this->error_level == 1 ) {
			print "<p>Error in: $file At line: $line</p>";
			print "<p>Message: $message</p>";
		}
	}
	
	/**
	 * Convenient functions to help self-documenting
	 * code by stating type in function name.
	 * $log->error();
	 * $log->warn();
	 */
	 
	function error( $file, $line, $message )
	{
		if( $this->error_level == 1 ) {
			print "<p>Error in: $file At line: $line</p>";
			print "<p>Message: $message</p>";
		}
	}

	function warn( $file, $line, $message )
	{
		if( $this->error_level == 1 ) {
			print "<p>Error in: $file At line: $line</p>";
			print "<p>Message: $message</p>";
		}
	}
}


// error logging
// not a class, but hopes to be one someday
// that is why this is called events-class for event logger/not event handler
// will probably move this from a debug tool to a system monitor tool
// but will do for now
function log_err( $file, $line, $message )
{
	global $error_level;
	
		// debug
		//print "<p>Error in: $file At line: $line</p>";
		//print "<p>Message: $message</p>";
	
	
	if( $error_level == 1 ) {

	$category = 0;
	$message = "$message ($file, $line)";
	
	$dsn = array(
	    'phptype'  => 'mysql',
	    'username' => 'cityg_8',
	    'password' => 'gBhpj4Xj',
	    'hostspec' => 'db72c.pair.com',
	    'database' => 'cityg_dev',
		);
	
		$db =& DB::connect($dsn, $options);
	
	    //$this->db = DB::connect($dsn);
	
		if (DB::isError($db)) {
	    	die($db->getMessage());
		}
	
	/**
	 * /remark
	 * Unix time has greater resolution than MySQL
	 * datetime as far as I can tell.
	 */
  	 $sql = "INSERT INTO event_log SET";
	 $sql .= " timetamp=". time();
	 $sql .= " category=".$db->quote($category);
	 $sql .= ", message=".$db->quote($message);

	$db->query($sql);
                 
if (DB::isError($db)) {
    die($db->getMessage());
}      

	
	//	print "<p>Error in: $file At line: $line</p>";
	//	print "<p>Message: $message</p>";
	
	}
}

// write to trace log

function logto($msg) {
	

}

?>