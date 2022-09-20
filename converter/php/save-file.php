<?php

// Configuration file for CADViewer Community and CADViewer Enterprise version and standard settings
require 'CADViewer_config.php';



$http_origin = '';

if (isset($_SERVER['HTTP_ORIGIN'])) {
  $http_origin = $_SERVER['HTTP_ORIGIN'];
}
elseif (isset($_SERVER['HTTP_REFERER'])) {
  $http_origin = $_SERVER['HTTP_REFERER'];
}

// allow CORS or control it
if (true){
    header("Access-Control-Allow-Origin: $http_origin");
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
}
else{

	$allowed_domains = array(
	  'http://localhost:8080',
	  'http://localhost:8081',
	  'http://localhost',
	);

	if (in_array($http_origin, $allowed_domains))
	{
		header("Access-Control-Allow-Origin: $http_origin");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
	}	
}


$fullPath = $_POST['file'];
$file_content = $_POST['file_content'];

$custom_content = "";
try {
	if (isset($_POST['custom_content'])){
		$custom_content = $_POST['custom_content'];    // this is stuff to use to control content
	}
} catch (Exception $e) {
	// do nothing
}




//echo $custom_content;

$base64 = 0;

try {
	if (isset($_POST['base64'])){
		$base64 = $_POST['base64'];		
	}
} catch (Exception $e) {
	// do nothing
}

if ($base64==1){
	$file_content = base64_decode($file_content);	
}


$listtype = "";
try {
	if (isset($_POST['listtype'])) {
		$listtype = $_POST['listtype'];	
	}
} catch (Exception $e) {
	// do nothing
}

// we user a server side path 
if ($listtype == "serverfolder"){
	$fullPath = $home_dir . $fullPath;
}



$fullPath = urldecode($fullPath);


//echo 'XX'. $fullPath . 'XX';


if (strpos ( $fullPath , 'http' )>-1){

	$fullPath = str_replace(" ", "%20", $fullPath);
}

//echo "$fullPath";
//echo "$file_content";
echo "";

$basepath =    substr( $fullPath, 0, strrpos ( $fullPath , '/' ));

if (!file_exists($basepath)) {
	mkdir($basepath, 0777, true);
}

if (file_exists($fullPath)) {	
	unlink($fullPath);
//	rename($fullPath, $fullPath . '_old');
}


//$rand = rand ( 0 , 100000 );
//if ($fd = fopen ($fullPath . $rand, "w+")) {


if ($fd = fopen ($fullPath, "w+")) {
//echo "file open!";


	$pieces = str_split($file_content, 1024 * 4);
    foreach ($pieces as $piece) {
        fwrite($fd, $piece, strlen($piece));
    }
    fclose($fd);

//	fwrite($fd, $file_content);
//	fclose ($fd);	
		
	$time = time() + 1;
	touch($fullPath, $time);
		
 	echo "Succes";
	exit;
}

 	echo "Could not save file " . $fullPath;
	exit;

?>