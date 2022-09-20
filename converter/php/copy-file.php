<?php


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

$localfilename = $_POST['localfilename'];
$fullPath = $_POST['localdestination'];

$fullPath = urldecode($fullPath);


$file_content = file_get_contents($localfilename);


//echo "$fullPath";
//echo "$file_content";
//echo "";

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
	fwrite($fd, $file_content);
	fclose ($fd);	

//	copy ( string $source , string $dest
	
//	rename($fullPath ."x", $fullPath);
//	copy($fullPath . $rand, $fullPath);
		
	$time = time() + 1;
	touch($fullPath, $time);
	
//	if (touch($fullPath, $time)) {
//		echo $fullPath . ' modification time has been changed to present time';
//	} else {
//		echo 'Sorry, could not change modification time of ' . $fullPath;
//	}	
	
 	echo "Succes";
	exit;
}

 	echo "Could not save file " . $fullPath;
	exit;

?>