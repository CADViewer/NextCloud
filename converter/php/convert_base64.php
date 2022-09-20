<?php

	$input_file = $_POST['input_file'];
	$output_file = $_POST['output_file'];

echo $input_file . "  ";
	
echo $output_file . "  ";
	
	$base64_string = file_get_contents($input_file);
	$data = explode(',', $base64_string);			

	$ifp = fopen($output_file, "wb");
	fwrite($ifp, base64_decode($data[1]));
	fclose($ifp);

	if (file_exists($input_file)) {	
		unlink($input_file);
	}

	exit;
?>