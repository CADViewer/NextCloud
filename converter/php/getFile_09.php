<?php

require 'CADViewer_config.php';

// respond to preflights
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	// return only the headers and not the content
	// only allow CORS if we're doing a GET - i.e. no saving for now.
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
	exit;
}




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



$fileTag 	= $_GET['fileTag'];
$Type 		= $_GET['Type'];

$remainOnServer = 0;

try{
	$remainOnServer = $_GET['remainOnServer'];
} catch (Exception $e) {
		// none
}


if ($Type == "svg" )
	header('Content-type: image/svg+xml');

if ($Type == "svgz" ){
	header('Content-type: image/svg+xml');
	header('Content-Encoding: gzip');
}

$returnFile = $fileLocation . $fileTag . '.' . $Type;

$file_content = file_get_contents($returnFile);

echo $file_content;



if (file_exists($returnFile)){
	if ($remainOnServer==0)
		unlink($returnFile);
}


?>
