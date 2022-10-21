<?php
header("Access-Control-Allow-Origin: http://localhost");
header("Content-Type: application/json; charset=UTF-8");

if (isset($_GET["user"]))
	$username = $_GET["user"];
else
	$username = "root";

if (isset($_GET["user"]))
	$password = $_GET["pwd"];
else
	$password = "";


$conn = new mysqli("localhost", $username, $password, "cadviewer_handles_public_demo");


// GET THE CONTENT OF SAMPLE DRAWING

$sql = "SELECT cv_id, handle, entity, block, customgroup1 FROM one_of_simple";

$result = $conn->query($sql);

$outp = "";
while($row = $result->fetch_assoc()) {
    if ($outp != "") {$outp .= ",";}

	$outp .= '{"Handle":"'   . trim($row["handle"])        . '",';
	$outp .= '"ID":"'   . trim($row["cv_id"])        . '",';
	$outp .= '"Entity":"'   . trim($row["entity"])        . '",';
	$outp .= '"Block":"'   . trim($row["block"])        . '",';
	$outp .= '"CustomGroup1":"'   . trim($row["customgroup1"])        . '"}';

}
$conn->close();

$merged_outp ='{"records":['.$outp.']}';

echo($merged_outp);
?>