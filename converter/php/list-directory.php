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


    // Define the full path to your folder from root

$path = $_POST['directory'];
$path_url = $_POST['directoryurl'];
$listtype = $_POST['listtype'];

/*

if (isset($_POST['listtype'])) {
	$listtype = $_POST['listtype'];
}
*/
/*

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
*/


	if ($debug) {
		if ($fd_log = fopen ("list-directory_log.txt", "a+")) {
			fwrite($fd_log, "list directory  v2.0.02  \r\n r\n");
			fwrite($fd_log, "Path $path  listtype XX $listtype XX homedir $home_dir pathURL $path_url \r\n");
		}		
	}


	// we user a server side path 
	if ($listtype == "serverfolder"){
		$path = $home_dir . $path;
	}



	if ($debug) {
			fwrite($fd_log, "Path $path  listtype XX $listtype XX  \r\n");
	}



    // Open the folder

    $dir_handle = @opendir($path) or die("Unable to open $path");


    // Loop through the files

    while ($file = readdir($dir_handle)) {


	if ($debug) {
			fwrite($fd_log, "$file r\n");
		}		
	



    if($file == "." || $file == ".." || $file == "list-directory.php" || $file == "delete-file.php" || $file == "load-header.php" || $file == "filenames.rw" || $file == "load-file.php" || $file == "save-file-p1.php" )
        continue;


	$pos = strpos($file, ".");
	if ($pos == false)
		continue;     // no file extension, therefore a subdirectory

		$fname = $path_url . $file;

#        echo "<a href=\"$fname\">$file</a><br />";

        echo "<br>$file";
#        echo "$file<br/>";



    }



    // Close

    closedir($dir_handle);



?>