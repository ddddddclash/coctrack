<?php
	Class LogError {
	
	var $errorFile;
	var $errorString;
	var $verbose;
	var $logTime;
	var $writeMode = 'a';
	var $postvalue;
	
	function LogError($filename,$logTime = true, $verbose = true){
		$this->errorFile = $filename;
		$this->verbose = $verbose;
		$this->logTime = $logTime;
	}
	
	function setLogTime($logTime){ $this->logTime = $logTime; }
	function setVerbose($verbose){ $this->verbose = $verbose; }
	function setWriteMode($writeMode) {$this->writeMode = $writeMode; }
	function setPost($postvalue) {$this->postvalue = $postvalue; }
	
	function addError($err){
		if ($this->logTime)
			$err .= "\r\nError Time: ".$this->logDateTime()."\r\n";
		$this->errorString .= "\r\n".$err;
	}
	
	function verbose(){
		$out = "------------------------------------------------------------------------------------\r\n";
		$out .= "Write Time: ". $this->logDateTime() . "\r\n";
		$out .= "PHP_SELF: " . $_SERVER['PHP_SELF'] . "\r\n";
		$out .= "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\r\n";
		$out .= "REMOTE_ADDR: " . $_SERVER['REMOTE_ADDR'] . "\r\n";
		$out .= "REMOTE_HOST: " . $_SERVER['REMOTE_HOST'] . "\r\n";
		$out .= "SERVER_NAME: " . $_SERVER['SERVER_NAME'] . "\r\n";
		$out .= "DOCUMENT_ROOT: " . $_SERVER['DOCUMENT_ROOT'] . "\r\n";
		$out .= "HTTP_REFERER: " . $_SERVER['HTTP_REFERER'] . "\r\n";
		$out .= "HTTP_USER_AGENT: " . $_SERVER['HTTP_USER_AGENT'] . "\r\n";
		$out .= "REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD'] . "\r\n";
		$out .= "QUERY_STRING: " . $_SERVER['QUERY_STRING'] . "\r\n";
		if (!empty($this->postvalue)){
			$out .= "POST info:\r\n";
			foreach ($this->postvalue as $name => $value){
				$out .= "\t[".$name."] => ".$value."\r\n";
			}
		}
		
		
		$out .= "------------------------------------------------------------------------------------\r\n";
		return $out;
	}
	
	function logDateTime(){
		$now = getDate();
		return $now['month']." ".$now['mday']." ".$now['year']." - ".
			$now['hours'].":".$now['minutes'].":".$now['seconds'];
	}
	
	function writeError(){
		if (!empty($this->errorString)){
			$handle = fopen($this->errorFile, $this->writeMode);
			$errorString = $this->errorString;
			if ($this->verbose)
				$errorString .= $this->verbose();
			$errorString .= "*************************************************************************\r\n";
	
	        fwrite($handle, $errorString);
	        fclose($handle);
		}
	}
}
?>