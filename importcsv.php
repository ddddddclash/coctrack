<?php



$table = array_map('str_getcsv', file('uploads/Opponents.csv'));

echo "<table border='1'>";
foreach ($table as $rows => $row)
{
	echo "<tr>";
	foreach ($row as $col => $cell)
	{
		echo "<td>" . $cell . "</td>";
	}	
  echo "</tr> \r\n";
}	
echo "</table>";

?>

