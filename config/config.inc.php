<?php
 
  if (strcasecmp($_SERVER["HTTP_HOST"],"localhost") == 0) {		//Don't change
	// The following settings are for the Localhost / testing server
	 define ('THISPAGE',$_SERVER['PHP_SELF']);
 	 define('DB_SERVER', 'localhost');
 	 define('DB_USERNAME', 'coc');
  	 define('DB_PASSWORD', '43CKVdeaSJQEbTDa');
  	 define('DB_NAME', 'coctracker');
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
