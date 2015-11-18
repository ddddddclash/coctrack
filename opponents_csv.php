<?php
include('config/config.inc.php');

function csv_to_array($filename='', $delimiter=',')
{
    if(!file_exists($filename) || !is_readable($filename))
        return FALSE;

    $header = NULL;
    $data = array();
    if (($handle = fopen($filename, 'r')) !== FALSE)
    {
        while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
        {
            if(!$header)
                $header = $row;
            else
                $data[] = array_combine($header, $row);
        }
        fclose($handle);
    }
    return $data;
}
//$csv= csv_to_array('uploads/Opponents.csv',',');




$csv= array_map('str_getcsv', file('uploads/Opponents.csv'));

$head = array_shift($csv);

echo "Head <br>";
print_r($head);
echo "<br>content<br>";
print_r($csv);

/*
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
*/
?>