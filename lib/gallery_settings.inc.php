<?php
#####################################################################################
##																				    #
## 	Copyright 2005 Wartian Consulting                                               #
##																					#
#####################################################################################


##########################################################
## 	Important: DON'T CHANGE THE FOLLOWING LINE           #
##########################################################

if (strcasecmp($_SERVER["HTTP_HOST"],"localhost") == 0) {		//Don't change
	// The following settings are for the Localhost / testing server
	define("LOCAL",true);
	define("IMAGEMAGICK","/apache/ImageMagick/");
	define("FILE_BASE","/apache/htdocs/vww.com/");
	define("IMAGE_PATH","albums/");
	define("CACH_DIR", "cache/");
	define("ALBUM_DIR","albums/");
	define("DESC_DIR","cache/");

} else { 
	// The following settings are for the Remote / Live server
	define("LOCAL",false);
	define("IMAGEMAGICK","/usr/local/bin/");
	define("FILE_BASE","/home/vintagew/www/www/");
	define("IMAGE_PATH","albums/");
	define("CACH_DIR", "cache/");
	define("ALBUM_DIR","albums/");
	define("DESC_DIR","cache/");
}



// "$process_with" can either be "GD" or "IM" (ImageMagick).  I recommended "IM", but
// if you're having problems with thumbnail creation, try "GD".
$process_with = "IM"; 	//default "IM"


// How many thumbnails to show on an index page
$items_per_page	= 12;

define ("COLS","5");
define ("ROWS","3");

## Size of a thumbnail
$thumb_size	= 100;

## The default size of a viewed image
$default_size	= 400;

## What sizes are available for scaling
$viewsizes	= array(512,640,800,1024,1280,"Original");



## Paths array required for ImageMagick.class.php
$paths = Array();
$paths['fileBase'] = "/apache/htdocs/vww.com/";
$paths['urlBase'] = "http://localhost/vww.com/";
$paths['cache'] = "cache/";
$paths['albums'] = "albums/";
$paths['descriptions'] = "albums/";


?>