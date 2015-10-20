<?php


class Image {
	var $base;
	var $path;
	var $filename;
	var $width;
	var $height;
	var $size_string;
	var $_ImageTool;		//ImageTool object 
	
	function Image($base,$path,$filename){
		$this->base = $base;
		$this->path = $path;
		$this->filename = $filename;
		$this->getSize();
	}
	
	function getSize(){
		$srcsize = GetImageSize($this->base.$this->path.$this->filename);
		if ($srcsize[0]) {
			$this->size_string = "$srcsize[0] x $srcsize[1]";
			$this->width = $srcsize[0];
			$this->height = $srcsize[1];
		} else {
			$this->size_string = "unknown format";
		}
		return $this->size_string;
	}
	
	function setImageTool($imageTool){
		if ($imageTool)
			$this->_ImageTool = $imageTool;
		else
			die('Usage: setImageTool(new ImageTool_ImageMagick($imDir) [or new ImageTool_GD()])');
	}
	
	function getFilename(){
		return $this->filename;	
	}
	
	function getLink(){
		// Base and Path info should be stored in the Gallery object
		// echo "?base=$this->base&path=$this->path&filename=$this->filename";
		return $this->base.$this->path.$this->filename;
	}
	
	function resize($width,$height){
		$fileInfo = array("path"=>$this->path,"filename"=>$this->filename);
		return $this->_ImageTool->resize($fileInfo,$width,$height);
	}

}

?>