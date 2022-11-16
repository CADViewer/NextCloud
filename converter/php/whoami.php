<?php

echo 'Current script owner: ' . get_current_user();




	$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$pos1 = stripos($actual_link, "/cadviewer/");
	$httpHost = substr($actual_link, 0, $pos1+ 11);

	$currentpath = __FILE__;
	$pos1 = stripos($currentpath, "cadviewer");
	$home_dir = substr($currentpath, 0, $pos1+ 10);


echo "  " . $httpHost . "  " . $home_dir;

?>