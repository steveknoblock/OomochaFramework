<?php

/**
 * Determine environment
 */
 
 /**
  * Get language version by most backwardly compatible
  * method first.
  */
  $_ic['php_version'] = phpversion();
 
 // TODO: conditionally grab php array 
 //$_php = ini_get_all();
 
 // install context
 $_ic['register_globals'] = ini_get('register_globals');
 $_ic['magic_quotes_gpc'] = ini_get('magic_quotes_gpc');
 $_ic['magic_quotes_runtime'] = ini_get('magic_quotes_runtime');
 $_ic['magic_quotes_sybase'] = ini_get('magic_quotes_sybase');
 $_ic['allow_url_fopen'] = ini_get('allow_url_fopen');
 $_ic['file_uploads'] = ini_get('file_uploads');
 $_ic['memory_limit'] = ini_get('memory_limit');
 $_ic['safe_mode'] = ini_get('safe_mode');
 
 /**
  * Ideally this should be a module and use the framework for form
  * interaction during install.
  */
if( $_REQUEST['step'] == 'database' ) {

// display form here
// then set these values
// start out by guessing 'localhost'
// I refuse to create your database for you
 $_ic['database_host'] = '';
 $_ic['database_name'] = '';
 $_ic['database_user'] = '';
 $_ic['database_pass'] = '';
 
 
// should display warnings and critical issues to user
// need to differentiate, stop install on critical
// okay to go ahead on warnings
// try to connect
if( $failed_db_connect ) {
	$issues[] = array( 'Failed Database Connection', 'Could not establish a connection with the database using the information you submitted. Please check your information' );
}
 
} elseif( $_REQUEST['step'] == 'final' ) {

if( !empty( $issues ) ) {
// foreach over issues displaying them
// maybe display as read only checkboxes, then submit as form, if any are checked you know they are unresolved?
}

}
print "<pre>";
print_r( $_ic );
print "</pre>";


?>