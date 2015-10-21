<?php
 
if (strcasecmp($_SERVER["HTTP_HOST"],"localhost") == 0) {		
	//enable error reporting on Local host
	ini_set('display_startup_errors',1);
	ini_set('display_errors',1);
	error_reporting(-1);
	
	//Database settings for local database
	define ('THISPAGE',$_SERVER['PHP_SELF']);
	define('DB_SERVER', 'localhost');
	define('DB_USERNAME', 'cocAdmin');
	define('DB_PASSWORD', 'pUfFLSFBarhB3hPQ');
	define('DB_NAME', 'clashtrack');
	define('FILE_BASE','/htdocs/coctrack/');
	define('BASE_PATH', '/coctrack/');
	define('SITE_IMAGES',BASE_PATH.'images/');
	define('HOME_URL',BASE_PATH);
} else { 
	// The following settings are for the Remote / Live server
	 define ('THISPAGE',$_SERVER['PHP_SELF']);
 	 define('DB_SERVER', '');
 	 define('DB_USERNAME', '');
  	 define('DB_PASSWORD', '');
  	 define('DB_NAME', '');    
     define('FILE_BASE','');
  	 define('BASE_PATH', '/');
  	 define('SITE_IMAGES',BASE_PATH.'images/');
	 define('HOME_URL','');
}
  
?>
