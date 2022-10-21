<?php

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



	
}













	// we want to increase the execution time for time consuming conversions
	// 4 min is the maximum
	set_time_limit(240);

	$baseFile = $_POST['base_file'];
	$mergeFile = $_POST['merge_file'];
	$outFile = $_POST['out_file'];

	$executable = $dwgmergeLocation . '/' . $dwgmerge2020_executable;
	
	$cp_command_line = $executable . ' -base="' . $baseFile . '" -merge="' . $mergeFile . '" -out="' . $outFile . '" -over -lpath="' . $dwgmergeLocation .'"' ;
	
	echo '  command line merge: ' . $cp_command_line . '  ';
	
	exec($cp_command_line, $out, $return1);
	

//	if ($fd = fopen ($outFile, "w+")) {
//	//echo "file open!";
//		fwrite($fd, "dummy");
//		fclose ($fd);	
//	}
	
	echo $return1;
	
	exit;


?>
