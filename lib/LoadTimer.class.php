<? 
class LoadTimer {

	var $startTime ;

	function LoadTimer() {
		$this->startTime = $this->getTime();
	}
	
	function getTime(){
		$mtime = explode(" ",microtime());
		return $mtime[1] + $mtime[0];
	}

	function getTimePassed()
	{
		return $this->getTime() - $this->startTime;
	}
}
?>

