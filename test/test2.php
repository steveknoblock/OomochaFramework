<?php

print phpversion();

print "Breakpoint 1";

require_once 'DB.php';
require_once 'Auth.php'; // Authentication

//require_once '/usr/home/cityg/tools/PEAR/lib/php/DB.php';
//require_once '/usr/home/cityg/tools/PEAR/lib/php/Auth.php'; // Authentication

  $dsn = array(
	    'phptype'  => 'mysql',
	    'username' => 'cityg_8',
	    'password' => 'gBhpj4Xj',
	    'hostspec' => 'db72c.pair.com',
	    'database' => 'cityg_dev',
		);
	
	
		$db =& DB::connect($dsn);
	
	    //$db = DB::connect($dsn);
		
		if (DB::isError($db)) {
	    	die($db->getMessage());
				print "Breakpoint 2";
		} 
		
		print "Breakpoint 3";
		
function loginFunction()
{
    /*
     * Change the HTML output so that it fits to your
     * application.
     */
    echo "<form method=\"post\" action=\"{$PHP_SELF}\">";
    echo "<input type=\"text\" name=\"username\">";
    echo "<input type=\"password\" name=\"password\">";
    echo "<input type=\"submit\">";
    echo "</form>";
}

	   
$dsn = "mysql://cityg_8:gBhpj4Xj@db72c.pair.com/cityg_dev";
$params = array(
            "dsn" => $dsn,
            "table" => "users",
            "usernamecol" => "username",
            "passwordcol" => "password"
            );
			
$a = new Auth("DB", $params, "loginFunction");

print "Breakpoint 4";

$a->setSessionname('SPU_SITE');
$a->setExpire( 3600 ); // 60mins in seconds 

$a->start();

$username = $a->getUsername();
print "Username: $username";
//log_err( __FILE__, __LINE__, "Status: ". $a->getStatus());
print "Status: ". $a->getStatus();

/**

Report:


THIS IS ALL WRONG: I was setting optional to 1 or 0 not true or false.

The docs are not very clear about the behavoir of Auth under all options and conditions.

With no callback defined: GET request with optional at zero produced default internal login form. With optional at one, produced the same.

With a dummy callback defined: GET request value of zero for optional displayed the callback login. A value of one did the same.

LOGGEDIN	GET		callback	optional	result
N			Y		N			0			internal form
N			Y		N			1			internal form
N			Y		Y			0			callback form
N			Y		Y			1			callback form


The form action for the internal form is (wisely) set to PHP_SELF.

Okay, if I submit the internal form, with bad data, it redisplays the form saying "Wrong login data." I also can display the status using getStatus().

*/

?>