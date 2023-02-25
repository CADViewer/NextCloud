<?php
	
	require 'CADViewer_config.php';
   
	$serverPath = $_POST['serverPath'];
	$numberOfFiles = 1;
	$originalFileName = $_POST['org_fileName_0'];


	$listtype = "";

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
	
	// we user a server side path 
	if ($listtype == "serverfolder"){
		$serverPath = $home_dir . $serverPath;
	}
	

	try {

		for ($i = 0; $i < $numberOfFiles; $i++) {
			$file_id = "fileName_". $i;
			$fileNameArray[$i] = $_POST[$file_id];

			$rot_id = "rotation_". $i;
			$rotationArray[$i] = $_POST[$rot_id];

			$paper_id = "page_format_". $i;
			$paperSizeArray[$i] = $_POST[$paper_id];

			$resolution_id = "page_resolution_". $i;
			$pageResolutionArray[$i] = $_POST[$resolution_id];

			// 7.9.14
			$base64_string = file_get_contents($home_dir . '/converters/files/' .  urldecode($fileNameArray[$i]) . '_base64.png');
			$data = explode(',', $base64_string);

			// 7.9.14
			$temp_file_name = rand();
			$output_file = $home_dir . '/converters/files/' . $temp_file_name .'.png';
//			$output_file = $home_dir . '/converters/files/' . $fileNameArray[$i] .'.png';
			$ifp = fopen($output_file, "wb");
			fwrite($ifp, base64_decode($data[1]));
			fclose($ifp);

			//unlink($home_dir . '/converters/files/' . $fileNameArray[$i] . '_base64.png');
//			$final_fileName = $fileNameArray[$i] . '.pdf';
			$final_fileName = $temp_file_name . '.pdf';

			// echo 'calling AutoXchange';			
			$command_line = $converterLocation . $ax2023_executable . ' -i="' . $output_file .'"' . ' -o="' . $home_dir . '/converters/files/' . $final_fileName .'" -f=pdf -' . $paperSizeArray[$i] .' -' . $rotationArray[$i];
			
			//echo $command_line;

			exec( $command_line, $out, $return1);			
			copy($home_dir . '/converters/files/' . $final_fileName, $home_dir . '/converters/files/' . urldecode($fileNameArray[$i]) . '.pdf');
			// unlink pngs
			unlink($home_dir . '/converters/files/' . $final_fileName);
			unlink($home_dir . '/converters/files/' . urldecode($fileNameArray[$i]) . '_base64.png');


			/*
			$html = file_get_contents($home_dir . '/converters/files/' .  urldecode($fileNameArray[$i]) . '.html');
			$html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
			$ifp = fopen($home_dir . '/converters/files/' .  urldecode($fileNameArray[$i]) . '.html', "wb");
			fwrite($ifp, $html);
			*/

			
			// 7.9.14 the reply needs to the server side path:

			echo $httpHost. '/converters/files/' . urldecode($fileNameArray[$i]) . '.pdf';

//			echo $fileNameArray[$i] . '.pdf';
			exit;

		}
	} catch (Exception $e) {
		echo 'Caught exception 1: ',  $e->getMessage(), "\n";
	}

	exit;

?>