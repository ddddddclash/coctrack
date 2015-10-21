<?php
include('config/config.inc.php');
$submit = false;
$image = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['clan_name'] != null){
	    $submit = true;	    
	    if (isset($_FILES['fileToUpload']) && $_FILES['fileToUpload']['size'] > 0) {
		    $image = mysql_real_escape_string(file_get_contents($_FILES['fileToUpload']['tmp_name']));
	    } 
	    //else {
	    //		die("no image");
	    //}
	    
    
	   $db = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);	   
	   if($db->connect_errno > 0){
		  die('Unable to connect to database [' . $db->connect_error . ']');
	   }
	   	   
	   $sql = "Select clan_id from clan where clan_tag = '".$_POST["clan_tag"]."'";
	   
	   if(!$result = $db->query($sql)){
		  die('There was an error running the query [' . $db->error . ']');
	   }
	   
	   while($row = $result->fetch_assoc()){
		  $clan_id =  $row['clan_id'];
	   }
	   
	   $result->free();
	   
	   echo "clan_id = ".$clan_id;
	   
	   $sql = "INSERT INTO clan_snapshot (clan_id, screenshot,badge_level, total_points, wars_won, num_members, clan_type, required_trophies, war_frequency,clan_location,clan_xp,clan_xp_goal) VALUES ('".$clan_id."','".$image."','".$_POST["badge_level"]."','".$_POST["total_points"]."','".$_POST["wars_won"]."','".$_POST["num_members"]."','".$_POST["clan_type"]."','".$_POST["required_trophies"]."','".$_POST["war_frequency"]."','".$_POST["clan_location"]."','" .$_POST["clan_xp"]."','".$_POST["clan_xp_goal"]."')";
	   
	   if ($db->query($sql) === TRUE) {
		  echo "New record created successfully";
	   } else {
		  echo "Error: " . $sql . "<br>" . $db->error;
	   }
	   
	   $db->close();
    
    }
    //else {
	//    die("no clan name");
    //}
 }

?>




<? if (!$submit): ?>
<form action="clantab.php" method="post" enctype="multipart/form-data" name="ClanTab">
	Clan Name: <input type="text" name="clan_name" /><br />
     Badge Level: <input type="text" name="badge_level" /><br />
     Total Points: <input type="text" name="total_points" /><br />
     Wars Won: <input type="text" name="wars_won" /><br />
     Members: <input type="text" name="num_members" /><br />
     Type: <input type="text" name="clan_type" /><br />
     Required trophies: <input type="text" name="required_trophies" /><br />
     War Frequency: <input type="text" name="war_frequency" /><br />
     Clan Location: <input type="text" name="clan_location" /><br />
     Clan Tag: <input type="text" name="clan_tag" /><br />
	Experience: <input type="text" name="clan_xp" /><br />
     Experience Goal: <input type="text" name="clan_xp_goal" /><br /> 
     <input type="file" name="fileToUpload" id="fileToUpload">
	<input type="submit" value="Submit" name="submit">
     
</form>
<? else: ?>
	Clan Name: <?=$_POST["clan_name"]?> <br />
     Badge Level: <?=$_POST["badge_level"]?><br />
     Total Points: <?=$_POST["total_points"]?><br />
     Wars Won: <?=$_POST["wars_won"]?><br />
     Members: <?=$_POST["num_members"]?><br />
     Type:<?=$_POST["clan_type"]?><br />
     Required trophies: <?=$_POST["required_trophies"]?><br />
     War Frequency:<?=$_POST["war_frequency"]?> <br />
     Clan Location: <?=$_POST["clan_location"]?><br />
     Clan Tag: <?=$_POST["clan_tag"]?><br />
	Experience: <?=$_POST["clan_xp"]?><br />
     Experience Goal:<?=$_POST["clan_xp_goal"]?> 
	
<? endif;?>