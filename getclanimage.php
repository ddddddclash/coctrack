<?php

include('config/config.inc.php');

 $db = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
	   if($db->connect_errno > 0){
		  die('Unable to connect to database [' . $db->connect_error . ']');
	   }
	   
	   $sql = "SELECT created_at, screenshot FROM clan_snapshot WHERE clan_id = 1 AND screenshot IS NOT NULL";
	   
	   if(!$result = $db->query($sql)){
		  die('There was an error running the query [' . $db->error . ']');
	   }
	   
	  
	   while($row = $result->fetch_assoc()){
		echo  "Created at: ".$row['created_at'].'<img src="data:image/jpg;base64,'.base64_encode($row['screenshot']).'"><br>';
		//$temp = $row['screenshot'];
	   }
	   //header("Content-type: image/jpeg");
	   //echo $temp;
	   $result->free();
	   

?>


