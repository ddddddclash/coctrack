<?php
include("image.class.php");


class GalleryImage extends Image{
	var $description;
	var $descfile;
	var $suffix = "__desc.txt";
	var $desc_path;
	
	/*
 	 * Constructor function
 	 */
	function GalleryImage($base,$path,$filename,$type,$desc_base = ""){
		parent::Image($base,$path,$filename,$type);
		$this->desc_path = $desc_base == "" ? $base : $desc_base;
		$this->setDescFile();
	}
	
	function setFileSuffix($s){
		$this->suffix = $s;
		$this->setDescFile();
	}
	
	function setDescFile(){
		$this->descfile = $this->desc_path.$this->path.$this->filename.$this->suffix;
	}
	
	/*
 	 * Read image description from file
 	 */
	function getDescription(){
		if (file_exists($this->descfile))
			return file_get_contents($this->descfile);
		else
			return "";
		
	}
	
	/*
 	 * Write image description to a file 
 	 */
	 function writeDescription($description){
		$description = ereg_replace("\n", "<br>", $description);
	 	$handle = fopen($this->descfile, 'w');
        fwrite($handle, $description);
        fclose($handle);
     }
	 
	 
	/*
	 * Returns the path to the thumbnail.  If no thumbnail exists the current image tool
	 * is used to create a thumbnail. 
	 */
	function getThumbnail($w,$h){
		$fileInfo = array("path"=>$this->path,"filename"=>$this->filename);
		return $this->_ImageTool->resize($fileInfo,$w,$h);
	}
	
}

?>