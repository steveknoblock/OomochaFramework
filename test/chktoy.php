<?php 



require_once '/usr/home/cityg/tools/Smarty/Smarty.class.php';

        $smarty_dir = "/usr/home/cityg/web/phphelp/Smarty/"; //NO trailing slash!
        $smarty_template_dir = "$smarty_dir/templates/"; //NO trailing slash!
        $smarty_compile_dir = "$smarty_dir/templates_c"; //NO trailing slash!


        $smarty = new Smarty;
        $smarty->compile_check = TRUE;
        $smarty->template_dir = $smarty_template_dir;
        $smarty->compile_dir =  $smarty_compile_dir;
		

		/*
		
// this would be better, as the Smarty manual says
define('SMARTY_DIR', '/usr/home/cityg/tools/Smarty/'); // SMARTY_DIR Must end with a trailing slash
require_once SMARTY_DIR.'Smarty.class.php';

// this is my own custom constant, not a Smarty defined one
define('SMARTY_DATA', '/usr/home/cityg/web/phphelp/Smarty'); // NO TRAILING SLASH unlike SMARTY_DIR
*/

$smarty->assign('cust_checkboxes', array( 
            1000 => 'Joe Schmoe', 
            1001 => 'Jack Smith', 
            1002 => 'Jane Johnson', 
            1003 => 'Charlie Brown')); 
$smarty->assign('customer_id', 1001);
 
$smarty->display('testchktoy.tpl'); 



/* Now, the interesting thing is that

$smarty->assign('customer_id', 1001);

renders checked, but

$smarty->assign('customer_id', 'Jack Smith');

does not.

This appears to be the opposite of what I observe using my form handler with Smarty.


*/



?>