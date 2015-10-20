<?php
include_once("gallery_settings.inc.php");
include_once("LoadTimer.class.php"); 	
$page_timer = new LoadTimer();
include_once("gallery.class.php");
include_once("ImageTools/ImageMagick.class.php");

$IM_paths = Array(	"imageMagick"	=> IMAGEMAGICK,
					"fileBase"		=> FILE_BASE,
					"source"		=> ALBUM_DIR,
					"dest"			=> CACH_DIR		);



//Testing Parameters
$overwrite = $_GET['overwrite'];		// default false
$verbose = $_GET['verbose'];						// default false
$show_times = $_GET['show_times'];					// default false

//Other Parameters
$d = $_REQUEST['dir'];
$p = $_REQUEST['page'];
$img_id = $_REQUEST['img_id'];
$disp_size = $_REQUEST['disp_size'];
$admin_mode = $_REQUEST['admin_mode'];
$description = $_REQUEST['description'];
$mode = $_COOKIE['mode'];
if (!isSet($mode)) $mode = 'user';

if (isSet($description))
	$description = stripslashes($description);


$image_tool = new ImageMagick($IM_paths,$verbose);
$image_tool->overwrite = $overwrite;

if (!isSet($d)) $d = 0;	
$gallery = new Gallery(&$image_tool,$verbose,$d);
$dirName = $gallery->getDirName();


if (!isSet($p)) $p = 1;


//$dirs = $gallery->getDirs();
//$gallery->getImages($dirs[$d]);

if(isSet($img_id)){
	$index_img = $gallery->_imageTool->makeFolder($dirName."/");
	$gallery->setImage($img_id);
	$img = $gallery->getImage();
	if($admin_mode && isSet($description)){
		$img->writeDescription($description);
		$admin_mode = false;
	}	
	$prev = $gallery->getPrevImage();
	$img_sized = $img->resize(512,384);
	//$img_sized = $img->resize(672,429);
	$img_name = $img->getLink();
	$next = $gallery->getNextImage();
	$description = $img->getDescription();
}
$gallery->makePages(COLS,ROWS);
$link_temp = '<a href=\"show_dir.php?page={$page}&dir='.$d.'\">{$page}</a> ';
$page_links = $gallery->pageLinks($p,$link_temp);


if ($verbose)
	echo "Main Code Execution Time: ".$page_timer->getTimePassed()."<br>";
?>