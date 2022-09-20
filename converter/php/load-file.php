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


if ($checkorigin){
	
	if (in_array($http_origin, $allowed_domains))
	{
		header("Access-Control-Allow-Origin: $http_origin");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
	}	

}
else{
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
	header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
}


$fullPath = "";
$loadtype = "";
$listtype = "";

if (!empty($_GET)) {
	if (isset($_GET['file'])) {
		$fullPath = $_GET['file'];
	}
}
else{
    // no data passed by get
	if (isset($_POST['file'])) {
		$fullPath = $_POST['file'];
	}
}


if (!empty($_GET)) {
	if (isset($_GET['loadtype'])) {
		$loadtype = $_GET['loadtype'];
	}
}
else{
    // no data passed by get
	if (isset($_POST['loadtype'])) {
		$loadtype = $_POST['loadtype'];
	}
}


if (!empty($_GET)) {
	if (isset($_GET['listtype'])) {
		$listtype = $_GET['listtype'];
	}
}
else{
    // no data passed by get
	if (isset($_POST['listtype'])) {
		$listtype = $_POST['listtype'];
	}
}


//echo "XX loadtype:" . $loadtype ."    listtype:" . $listtype. "  ". $fullPath;

// load languages app dir
if ( $loadtype == "languagefile"){
	$fullPath = $home_dir_app . $fullPath;
}

// menu file app dir
if ( $loadtype == "menufile"){
	$fullPath = $home_dir_app . $fullPath;
}

// home dir . for server location
if ( $loadtype == "serverfilelist"){
	$fullPath = $home_dir . $fullPath;
}


//echo "  fullPath:" . $fullPath. "XYZXYZ";

$contents = '';

$fp = "";

if ($fd = fopen ($fullPath, "rb")) {
    while(!feof($fd)) {
//        $buffer = fread($fd, 2048);
//        echo $buffer;
    	$contents .= fread($fd, 8192);
    }
	fclose ($fd);

}
else{
	echo "cannot open the file " . $fullPath;
}

$lastIndex = strripos($fullPath, '.');
$outputFormat = substr($fullPath, $lastIndex+1);

//echo "  ". $outputFormat . "   ";

if ($outputFormat == 'svg') 
	header('Content-type: image/svg+xml');


echo $contents;

exit;

/*



if ($outputFormat == "json") 
	header('Content-Type : application/json');


//header('Content-type: text/plain');


echo $contents;

*/


/*

if ( $outputFormat ==  'png' )
	header('Content-Type : image/png');

else
if ($outputFormat == 'jpg') 
	header('Content-Type : image/jpeg');
else
if ($outputFormat == 'jpeg') 
	header('Content-Type : image/jpeg');
else
if ($outputFormat == 'gif') 
	header('Content-Type : image/gif');
else
else
else
	header('Content-type: text/plain');


exit;
*/

?>