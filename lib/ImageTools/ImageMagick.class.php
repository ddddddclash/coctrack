<?php

##################################################################################################
##																								##
##	Requires $paths array																		##
##		$paths = Array();																		##
##		$paths['imageMagick'] = "[path to ImageMagick dir]";									##
##		$paths['fileBase'] = "[File Path to base dir include trailing /]";						##
##		$paths['source'] = "[Image source dir relative to 'fileBase' include trailing /]";		##
##		$paths['dest'] = "[Image destination dir relative to 'fileBase' include trailing /]";	##
##																								##
##################################################################################################


class ImageMagick {
	var $imagemagick_dir;
	var $file_base;
	var $source_dir;
	var $dest_dir;
	var $verbose;
	var $version;
	var $overwrite 	= false;
	var $quality	= 60;
	
	function ImageMagick($paths,$verbose=false){
		$this->verbose = $verbose;
		if ($this->verbose)
			echo "Constructor function called for New ImageMagick Object<br>\n";
		$this->setPaths($paths);
		$this->detectVersion();
	}
	
	function detectVersion(){
		if ($this->verbose){ echo "Detect Version<br>";}
		$command = IMAGEMAGICK."convert -version";
		exec($command, $returnarray, $returnvalue);
		if($returnvalue) {
			$this->version = "ERROR";
			if ($this->verbose){ 
				echo "<strong>Error:</strong><br><pre>";
				print_r($returnarray);
				echo "</pre>";
			}
		} else {
			
			$vstart = strpos($returnarray[0], "ImageMagick ") + strlen("ImageMagick ");
			$vend = strpos($returnarray[0], " ", $vstart);
			$this->version = substr($returnarray[0], $vstart, $vend - $vstart);
			if ($this->verbose){ echo "Version: $this->version <br>";}
		}
		
	}
	
	
	function getFirst4($dir){
		if ($this->verbose){
			echo "ImageMagick.class.php->getFirst4()<br>\n";
			echo "Dir = ".$this->file_base.$this->source_dir.$dir."<br>";
		}
		$temps = $this->file_base.$this->source_dir.$dir;
		$i = 0;
		$files = array();
		if (is_dir($temps)) {
			if ($this->verbose){ echo "Is dir<br>";}
			if ($dh = opendir($temps)) {
				if ($this->verbose){ echo "open dir<br>";}
	        	while (($file = readdir($dh)) && $i <4) {
					if ($this->verbose){ echo "File = $file <br>";}
					if(is_file($temps.$file)) {	
						if (preg_match("/\.jp(g|eg)$/i",$file)) {
						  $type = "jpeg";
						} elseif (preg_match("/\.gif$/i",$file)) {
						  $type = "gif";
						} elseif (preg_match("/\.png$/i",$file)) {
						  $type = "png";
						}
						if ($type){
							$files[$i++]  = array("path"=>$dir,"filename"=>$file);
						}
						$type = "";
					}
	        	}
	        	closedir($dh);
	    	}
		}
		return $files;
	}
	
	function makeFolder($dir){
		if ($this->verbose){
			echo "ImageMagick.class.php->makeFolder()<br>\n";
			echo "Dir = $dir <br>";
		}
		$fileInfo = $this->getFirst4($dir);
		
		$file1 = $this->file_base.$this->resize($fileInfo[0],100,100);
		$file2 = $this->file_base.$this->resize($fileInfo[1],100,100);
		$file3 = $this->file_base.$this->resize($fileInfo[2],100,100);
		$file4 = $this->file_base.$this->resize($fileInfo[3],100,100);
		$dest = $this->file_base. $this->dest_dir .$fileInfo1['path'].$dir."_folder.jpg";
		
		$options = "-frame 3 -geometry 40x -tile 2x2 \"$file1\" \"$file2\" \"$file3\" \"$file4\" \"$dest\"";
		
		if (!file_exists($dest) || $this->overwrite){
			if ($this->montage($options))
				if (file_exists($dest))
					return $this->dest_dir .$fileInfo1['path'].$dir."_folder.jpg";
			return false;
		}
		else {
			if ($this->verbose) echo "Montage exists didn't overwrite	<br>\n";
			return $this->dest_dir .$fileInfo1['path'].$dir."_folder.jpg";
		}
	}

	
	function resize($fileInfo, $width, $height, $q=-1){
		if ($this->verbose) echo "ImageMagick.class.php->resize()<br>\n";

		$quality =  (q > 0 && q <=100) ? "-quality $q" : "-quality $this->quality";
		$method = '';//'>'; 		// '>' = keep aspect '!' = fit
		$source = $this->file_base. $this->source_dir .$fileInfo['path']. $fileInfo['filename'];
		$destfile = $fileInfo['filename']."_as{$width}x{$height}.jpg";
		$dest = $this->file_base. $this->dest_dir .$fileInfo['path'].$destfile;
		
		$this->_checkDir($this->file_base. $this->dest_dir .$fileInfo['path']);
		if ($this->version == "4.2.9"){
			$options = "$quality -antialias -geometry \"${width}x${height}{$method}\" \"${source}\" \"${dest}\"";
		} else {
	   		$options = "$quality -antialias -size \"${width}x${height}{$method}\" \"${source}\"  -resize \"${width}x${height}{$method}\" +profile \"*\" \"${dest}\"";
		}
		
		/*elseif ($this->version == "5.4.9"){
	   		$options = "$quality -antialias -size \"${width}x${height}{$method}\" \"${source}\"  -resize \"${width}x${height}{$method}\" +profile \"*\" \"${dest}\"";
		} elseif ($this->version == "6.2.5"){
	   		$options = "$quality -antialias -size \"${width}x${height}{$method}\" \"${source}\"  -resize \"${width}x${height}{$method}\" +profile \"*\" \"${dest}\"";
		} elseif ($this->version == "6.2.7"){
	   		$options = "$quality -antialias -size \"${width}x${height}{$method}\" \"${source}\"  -resize \"${width}x${height}{$method}\" +profile \"*\" \"${dest}\"";
		} elseif ($this->version == "6.3.0"){
	   		$options = "$quality -antialias -size \"${width}x${height}{$method}\" \"${source}\"  -resize \"${width}x${height}{$method}\" +profile \"*\" \"${dest}\"";
		} else {
			$options = "";
			die ("ImageMagick Version ".$this->version." is curretly unsuported.");
		}*/
			
		if (!$this->isCached($source,$dest) || $this->overwrite){
			if ($this->convert($options))
				if (file_exists($dest))
					return $this->dest_dir .$fileInfo['path']. $destfile;
			return false;
		}
		else {
			if ($this->verbose) echo "File exists didn't overwrite	<br>\n";
			return $this->dest_dir .$fileInfo['path']. $destfile;
		}
	}
	
	function setPaths($paths){
		if (!is_array($paths)|| empty($paths['imageMagick']))
			die("Valid 'paths' array required. See ImageMagick.class.php for details.");
				
		$this->imagemagick_dir = $paths['imageMagick'];
		$this->file_base = $paths['fileBase'];
		$this->source_dir = $paths['source'];
		$this->dest_dir = $paths['dest'];
	}
	
	function _checkDir($dir){
		if (!file_exists($dir)){
			if ($this->verbose) echo "Mkdir";
			mkdir($dir);
		}
	}
	

	
	function montage($options){
		if ($this->verbose) echo "ImageMagick.class.php->montage()<br>\n";
		$command = $this->imagemagick_dir."montage $options";
		
		if ($this->verbose) echo "<tt>$command</tt>	<br>\n";
		exec($command, $returnarray, $returnvalue);
		if($returnvalue) {
			if($this->verbose) echo "Montage failed\n";
			return false;
		} else {
			return true;
		}
	}
	
	
	function convert($options){
		if ($this->verbose) echo "ImageMagick.class.php->convert()<br>\n";
		
		$command = $this->imagemagick_dir."convert $options";
		if ($this->verbose) echo "<tt>$command</tt>	<br>\n";
		exec($command, $returnarray, $returnvalue);
		if($returnvalue) {
			if($this->verbose){ 
				echo "Convert failed\n<br>";
				print_r($returnarray);
			}
			return false;
		} else {
			return true;
		}
	}
	
	function isCached($source,$dest){
		if(file_exists($dest)){
			if (filemtime($dest) > filemtime($source))
				return true;			
		}
		return false;
	}

}

?>
