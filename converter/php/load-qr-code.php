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

$QRcode = "Hello World!";
echo $QRcode;

exit;

?>