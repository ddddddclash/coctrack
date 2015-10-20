<?php
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

include('config/config.inc.php');

// Create connection
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "INSERT INTO member (clash_id, clash_name) VALUES ('LRCU9YG', 'Makara'); ";
$sql .= "INSERT INTO member (clash_id, clash_name) VALUES ('2V02GJ9V0','kewal');";
$sql .= "INSERT INTO member (clash_id, clash_name) VALUES ('990G8R82','WiFi x Smasher');";
$sql .= "INSERT INTO member (clash_id, clash_name) VALUES ('L222RPJL','souyhour');";
$sql .= "INSERT INTO member (clash_id, clash_name) VALUES ('VLU0QQY','Solarium');";
$sql .= "INSERT INTO member (clash_id, clash_name) VALUES ('80P8VYYP0','oppo_21');";

echo $sql;

if (mysqli_multi_query($conn, $sql)) {
    echo "New records created successfully";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
?> 