<?php
require_once "Auth.php";

function loginFunction()
{
    /*
     * Change the HTML output so that it fits to your
     * application.
     */
    echo "<form method=\"post\" action=\"auth.php\">";
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

$a = new Auth("DB", $params, "loginFunction", FALSE);

//$a->setSessionname('AUTHUSER');

$a->setExpire( 3600 ); // 60mins in seconds 

$a->start();

$username = $a->getUsername();
//print "<p>Username: $username</p>";

?>