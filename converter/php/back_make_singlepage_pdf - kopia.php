<?php
	
	require 'CADViewer_config.php';

	$serverPath = $_POST['serverPath'];
	$numberOfFiles = 1;
	$originalFileName = $_POST['org_fileName_0'];

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


			$base64_string = file_get_contents($home_dir . '/converters/files/' .  $fileNameArray[$i] . '_base64.png');
			$data = explode(',', $base64_string);
			$output_file = $home_dir . '/converters/files/' . $fileNameArray[$i] .'.png';
			$ifp = fopen($output_file, "wb");
			fwrite($ifp, base64_decode($data[1]));
			fclose($ifp);

			unlink($home_dir . '/converters/files/' . $fileNameArray[$i] . '_base64.png');
						
			// generate the document
//					
//			$randomNr = rand(100, 100000);
//			$final_fileName = 'batch_' . $randomNr . '.pdf';
			$final_fileName = $fileNameArray[$i] . '.pdf';

			// here we call AX
			// echo 'calling AutoXchange';			
			$command_line = $converterLocation . $ax2020_executable . ' -i="' .$home_dir . '/converters/files/' . $fileNameArray[$i] .'.png"' . ' -o="' . $home_dir . '/converters/files/' . $final_fileName .'" -f=pdf -' . $paperSizeArray[$i] .' -' . $rotationArray[$i];
			
			exec( $command_line, $out, $return1);
			
			
/*			
			//echo $command_line . '  ' . $return1 . '   ' ;

			$html_content = '<!DOCTYPE html><html><title>CADViewer - Print</title><head>';
			$html_content = $html_content . '<script src="../javascripts/jquery-2.2.3.js" type="text/javascript"></script>';

			$html_content = $html_content . '<script type="text/javascript">$(window).on(\'beforeunload\', function (){ $.ajax({url:\''. $httpPhpUrl . '/delete-file.php?file=' . $home_dir . '/converters/files/'  . $randomNr . '.pdf'  . '\', cache: false, success: function(html){}}); $.ajax({url:\''. $httpPhpUrl  .'/delete-file.php?file=' . $home_dir . '/converters/files/'   . $randomNr . '.html'  . '\', cache: false, success: function(html){}}) });';
			$html_content = $html_content . '$(window).on(\'unload\', function (){ $.ajax({url:\''. $httpPhpUrl . '/delete-file.php?file=' . $home_dir . '/converters/files/'   . $randomNr . '.pdf'  . '\', cache: false, success: function(html){}}); $.ajax({url:\''. $httpPhpUrl . '/delete-file.php?file=' . $home_dir . '/converters/files/'   . $randomNr . '.html'  . '\', cache: false, success: function(html){}}) });';
			$html_content = $html_content . 'window.onbeforeunload = function (){ $.ajax({url:\''. $httpPhpUrl . '/delete-file.php?file=' . $home_dir . '/converters/files/'   . $randomNr . '.pdf'  . '\', cache: false, success: function(html){}}); $.ajax({url:\''. $httpPhpUrl . '/delete-file.php?file=' . $home_dir . '/converters/files/'  . $randomNr . '.html'  . '\', cache: false, success: function(html){}}) };';
			$html_content = $html_content .  '</script><body><div id="pdf"><object width="1654" height="2339" type="application/pdf" data="' . $final_fileName . '" id="pdf_content"><p>Please install a PDF viewer, the CADViewer PDF cannot be displayed.</p></object></div></body></html>';

//			$html_file = $home_dir . '/converters/files/' . $randomNr . '.html';
			$html_file = $home_dir . '/converters/files/' . $fileNameArray[$i] . '.html';

			$ifp = fopen($html_file, "wb");
			fwrite($ifp, $html_content);
			fclose($ifp);

*/
			unlink($home_dir . '/converters/files/' . $fileNameArray[$i] .'.png');

//			echo $httpHost . '/converters/files/ ' . $randomNr . '.html';

			echo $fileNameArray[$i] . '.pdf';
			exit;
			
//*/			
		}
	} catch (Exception $e) {
		echo 'Caught exception 1: ',  $e->getMessage(), "\n";
	}

	exit;

?>