<?php
	ini_set('display_startup_errors',1);
	ini_set('display_errors',1);
	error_reporting(E_ALL);
	//error_reporting(error_reporting() & (-1 ^ E_DEPRECATED));

	include('config/config.inc.php');
	include('lib/database.class.php');
	
	$db = new Database(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
	$db->setTestMode(true);
	$tempList = $db->specialSelect('select * from member');	
	$db->close()
	

/*
// Create connection
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
	*/
	
	
	
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>
<body>
<?php
	print_r($tempList);
?>

</body>
</html>