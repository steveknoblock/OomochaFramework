<?php

/**********************************************************************
 *                          SweetPear Framework
 *                       Copyright 2006 Steve Knoblock
 *                                GPL License
 **********************************************************************/
  
	/**
	 * Configuration
	 * Configure application framework.
	 * @author Steve Knoblock
	 * @version 0
	 * @date begin: 
	 * @date revised: 
	 */
 
	/**
	 * Configuration
	 *
	 */

	 /**
	  * Database Sources
	  *
	  */
$dsn = array(
    'phptype'  => 'mysql',
    'username' => 'cityg_8',
    'password' => 'gBhpj4Xj',
    'hostspec' => 'db72c.pair.com',
    'database' => 'cityg_dev'
);

	/*
	 * Params for PEAR Auth class.
	 * 
	 */
   
$params = array(
	"dsn" => $db,
	"table" => "users",
	"usernamecol" => "username",
	"passwordcol" => "password"
);
	
	 /**
	  * Constants
	  *
	  */


	/**
	 * Paths
	 */
	define('LIBRARY_PATH', '');
	define('INSTALL_PATH', '');
	define('MODULE_PATH', '');

	 /**
	  * Form handler
	  */
	define("FORM_STICKY", 1);
	define("FORM_NORMAL", 0);
	define("HOMEPAGE_URL", 1); // is this correct? What is using this?


	/**
	 * Locations
	 */

	/**
	 * Note: Web root is the only url to not end in _URL since it is typed so frequently.
	 */
	define("DOMAIN", 'www.phphelp.com');
	define("WEB_ROOT", DOMAIN .'/test/projects/sandbox/mvc'); // no trailing slash
	define("THEME_STYLES_URL", 'themes/default/css'); // no trailing slash
	define("THEME_IMAGES_URL", 'themes/default/img'); // no trailing slash

	/**
	 * Misc
	 */

	define("SITE_TITLE", 'FarmFoody');
	define("ADMIN_EMAIL", 'knoblock_private@city-gallery.com');
	define("AGE_LIMIT", 18);
	define("SITE_CHARSET", 'text/html; charset=iso-8859-1');
	//define("SITE_CHARSET", 'text/html; charset=UTF-8');
	
	
?>