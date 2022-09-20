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

if (isset($_GET["room"]))
	$room = $_GET["room"];
else
	$room = "";

if (isset($_GET["floor"]))
	$floor = $_GET["floor"];
else
	$floor = "";

if (isset($_GET["bldg"]))
	$bldg = $_GET["bldg"];
else
	$bldg = "";


$conn = new mysqli("localhost", $username, $password, "vizquery_public_demo");


// FIRST GET THE DEPARTMENTS


$sql3 = "SELECT id, name, color_id FROM org2s ";
//echo $sql2;
$result_dept = $conn->query($sql3);

//echo "...." . $result_photos;

$departments ;

$outp_ph = "";
while($row = $result_dept->fetch_assoc()) {
	$departments[$row["id"]] = $row["name"];
}


// GET THE EMPLOYEES

$sql = "SELECT id, room_id, employee_number, phone, floor_id, org2_id, employeeName FROM employees ";

$result = $conn->query($sql);

$outp = "";
while($row = $result->fetch_assoc()) {
//	echo "room: " . $row["room_id"]." employee: " . $row["employee_number"]. " - Name: " . $row["employeeName"]. "<br>";

    if ($outp != "") {$outp .= ",";}
    $outp .= '{"ID":"'  . trim($row["id"]+100) . '",';

	// we do a simple split of cell content into first and last name
    $names = explode(" ", $row["employeeName"]);

	$outp .= '"LastName":"'   . trim($names[1])        . '",';
	$outp .= '"FirstName":"'   . trim($names[0])        . '",';
	$outp .= '"Phone":"'   . trim($row["phone"])        . '",';
	$outp .= '"Building":"'   . trim("")        . '",';
	$outp .= '"Floor":"'   . trim($row["floor_id"])        . '",';
	$outp .= '"Room":"'   . trim($row["id"]+100)        . '",';
//	we map the org2_id into the department table org2s
	$outp .= '"Department":"'   . trim( $departments[$row["org2_id"]])        . '",';
	$outp .= '"Division":"'. trim("")     . '",';
	$outp .= '"Category":"'   . trim("")        . '",';
	$outp .= '"Type":"'   . trim($row["employee_number"])        . '",';
	$outp .= '"Standard":"'. trim("")     . '"}';
}


//LOAD THE IMAGE TABLE FOR DISPLAY OF IMAGES

// this is a placeholder for a future more advanced demo
$outp_ph = "";

$conn->close();

$merged_outp ='{"records":['.$outp.'], "images":['.$outp_ph.']}';

echo($merged_outp);
?>