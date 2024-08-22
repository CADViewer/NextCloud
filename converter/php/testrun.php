<?php

// Construct path to converter folder
$currentpath = __FILE__;
$home_dir = explode("/cadviewer/", __FILE__)[0]."/cadviewer";

// include CADViewer config for be able to acces to the location of ax2024 executable file
$config_file = explode("/cadviewer/", __FILE__)[0]."/cadviewer/converter"."/php/CADViewer_config.php";
require($config_file);

$script = $converterLocation.$ax2023_executable." -verify_detail";
$script = str_replace("//", "/", $script);
$output_detail = shell_exec($script);

echo "<pre>";
echo "Run command:\n'".$script."' \nWith result:\n";
echo "\n=============\n";
echo $output_detail;
echo "\n=============\n";
echo "</pre>";