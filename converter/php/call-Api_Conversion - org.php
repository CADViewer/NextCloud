<?php
/*
    TMS RESTful API

    This script provides a RESTful API interface for web application accessing
    Tailor Made Software Conversion and Data Extraction engines


   Input:
        $_GET  = JSON (jsonp)formatted request according to TMS REST Api specification
		alternatively the content directly posted to the RESTful API, JSON formatted,

    Output: A formatted JSON HTTP response according to TME REST Api specification


    History:
		May 30          2016  - Version 1.4.05   - added external configuration file, infrastructure for calling through CV-JS
		Aug 12          2016  - Version 1.4.06   - updated for autoxchange defined through symbolic links
		Sep 11          2016  - Version 1.4.07   - minor additions
		Oct 18-20       2016  - Version 1.4.08   - modifications to config, ability to do conversions via POST
		Feb 2 - Apr 26  2017  - Version 1.4.09   - set to use AX2017
		Apr 26          2017  - Version 1.4.10   - renamed to call-Api_Conversion

		August 1        2017  - Version 1.5.01   - Setup for SVG conversions
		August 17       2017  - Version 1.5.02   - Quotes for RL/TL conversion parameter handling
		August 28       2017  - Version 1.5.03   - PDF creation for print
		August 31       2017  - Version 1.5.04   - Not delete for overlay
		September 6     2017  - Version 1.5.05   - Not delete for overlay, version implemented with remainOnServer
		October 30	    2017  - Version 1.5.06    - Url encoding of strings in json statmenet
		November 2	    2017  - Version 1.5.07    - Added interface to handle sharefile REST file sources
		May 2	        2018  - Version 1.5.08    - AX2019 support

		Nov 09	        2018  - Version 1.5.09    - Debug echo to local log file: call-Api_Conversion_log.txt
		Nov 11	        2018  - Version 1.5.10    - We are overwriting the configuration file settings with the contentlocation setting
		Jan 26	        2019  - Version 1.5.11    - Modyfying debug information
		Mar 29_apr 01	2019  - Version 1.5.12    - If server file, do not move input file to temp folder
		May 14       	2019  - Version 1.5.14    - Updated code to not remove source server file 
		
		June 19       	2019  - Version 1.6.01    - AX2020
		June 28       	2019  - Version 1.7.01    - On Windows, redirect to bat file for codepage control
		July  5      	2019  - Version 1.7.02    - On Windows, redirect to bat file for codepage control
		August  23      2019  - Version 1.7.04    - redirection to bat for codepage control, based on PHP_OS
		March   14      2020  - Version 2.0.01    - rewrite for Community and Enterprise use
		
*/

	// Configuration file for CADViewer Community and CADViewer Enterprise version and standard settings
	require 'CADViewer_config.php';

	// Configuration file for CADViewer Enterprise specific settings - Note!: CADViewer_config.php must be set!
	//require 'CADViewer_Enterprise_config.php';

	// only oncomment this, if loading drawing files from a sharepoint server via REST calls
	//require 'CV-JS_sharepoint_connector.php';

	// Settings of variables, not defined in configuration files
	$add_xpath = false;  	// NOTE: We are overwriting the configuration file settings with the contentlocation setting. 		
	$add_lpath = true;  	// we are adding lpath from configuration file
	$remainOnServer = 0;  	// general flag to tell pickup stream to to leave on server


	//  try-catch  1.5.09
	try {
		


	// we are setting the callback identifier to match the one defined in the originating document
	// 2016-01-28

	// create debug file
	if ($debug) {
		if ($fd_log = fopen ("call-Api_Conversion_log.txt", "a+")) {
			fwrite($fd_log, "NEW-FILE-CONVERSION-BEGIN   v1.7.02  \r\n r\n");
			fwrite($fd_log, "Opening call-Api_Conversion_log.txt for new conversion: \r\n");
		}		
	}
	
	if ($debug){
		//echo "before _ isset()x2 ";
		fwrite($fd_log, "before _ isset()x2  \r\n");

	}
	
/**
	if ($debug){
		//echo "";
		fwrite($fd, );

	}
**/	
	
	
	$post_request_flag = false;

    $callback ='tms_restful_api';
	$json_data = "";

    if(!empty($_GET['tms_restful_api']))
    {

//		if ($debug)	echo "_GET['tms_restful_api'] is set ";
		if ($debug){
			fwrite($fd_log, "_GET['tms_restful_api'] is set \r\n");
		}

			
        $callback = $_GET['tms_restful_api'];
		$json_data = $_GET['json'];

		$json_data = urldecode ( $json_data );

		//echo $json_data;


		if (empty($json_data)){
			$wrong_post_format = 1;
			$error_response = "{\"completedAction\":\"none\",\"errorCode\":\" \POST variable $_POST[request] is not defined \"}";
			//echo $error_response;
			// 2016-01-28 changed to be wrapped in return method
			echo $callback.'(' . json_encode($error_response) . ')';
			exit;
		}

    }


    if(!empty($_POST['json_data']))
    {

//		if ($debug)	echo "_POST['json_data'] is set ";
		if ($debug){
			fwrite($fd_log, "_POST['json_data'] is set  \r\n");
		}

        $callback = "";
		$json_data = $_POST['json_data'];

		$json_data = urldecode ( $json_data );
		//echo $json_data;

		$post_request_flag = true;

		if (empty($json_data)){
			$wrong_post_format = 1;
			$error_response = "{\"completedAction\":\"none\",\"errorCode\":\" \POST variable $_POST[request] is not defined \"}";
			//echo $error_response;
			// 2016-01-28 changed to be wrapped in return method
			echo $callback.'(' . json_encode($error_response) . ')';
			exit;
		}
    }


	$demo_mode = FALSE;

	// we are setting the authentication success to true
	$authentication_success = TRUE;

	//$stream_response_cgi= $httpHost . '/' . $callbackMethod ;
	$stream_response_cgi= $httpPhpUrl . '/' . $callbackMethod ;





	// this will be overwritten with _config file value
	$ld_lib_path = "/home/cadviewer/tms-restful-api/lib";


//if ($debug)	echo " first in file ";
		if ($debug){
			fwrite($fd_log, "first in file   \r\n");
		}


	$wrong_post_format = 0;
	$error_response = "{
									\"completedAction\":\"none\",
									\"errorCode\":\"incorrect formated http POST / JSON \"
									}";


//if ($debug)	echo "\$json_data: $json_data\n";
		if ($debug){
			fwrite($fd_log, "\$json_data: $json_data   \r\n");
		}

	// parse the _POST call / content retrieved from file_get_content

	$json_request = json_decode($json_data, true);


	if (empty($json_request)){

		$wrong_post_format = 1;
		$error_response = "{
										\"completedAction\":\"none\",
										\"errorCode\":\" JSON string expression incorrectly formatted \"
										}";
		//echo $error_response;


		// 2016-01-28    changed to be wrapped into callback method for jasonp

		// if jsonp then prepend callback, if standard post do not
		if ($post_request_flag)
			echo json_encode($error_response) ;
		else
			echo $callback.'(' . json_encode($error_response) . ')';

	}


	try {
		$delete_folder_status = isset($json_request['action']);
		if ($delete_folder_status){
			if ($json_request['action'] == 'clear_files_folder' ) {
				$files = glob($fileLocation.'*'); // get all file names
				foreach($files as $file){ // iterate files
			  		if (is_file($file))
			    		unlink($file); // delete file
				}
				echo "Files deleted! ";
				exit;
			}
		}
	} catch (Exception $e) {
		// none
	}





	$temp_var = "";
	try {

		$conv_status = isset($json_request['converters']);
		if ($conv_status) $temp_var = $json_request['converters'];
	} catch (Exception $e) {

	}


//if ($debug)	echo "\$json_request(conv): $temp_var\n";
//	echo "\$json_request: $json_request\n";
		if ($debug){
			fwrite($fd_log, "\$json_request(conv): $temp_var   \r\n");
		}


	$general_response= "{ \"error\": [] }";


//  list over available engines
	$engine_response=
"{\"installedEngines\":[
{\"converter\":\"AutoXchange AX2020\",\"version\":\"V1.00\",\"status\":\"active\"}]}";


	$engine_listing = [
		"internalListInstalledEngines" => [
			[
				"converter" => "AutoXchange AX2020",
				"version" => "V1.00",
				"executable" => $ax2020_executable,
				"location" => $converterLocation,
				"status" => "active"
			],
			[
				"converter" => "AutoXchange AX2020 DEMO",
				"version" => "V1.00",
				"executable" => $ax2020_executable,
				"location" => $converterLocation,
				"status" => "active"
			]
		]
	];

	$converter_list = $engine_listing['internalListInstalledEngines'];


	// template for conversion/data extraction response
	$conversion_response_template = "{
									\"completedAction\":\"xxx\",
									\"errorCode\":\"xxx\",
									\"converter\":\"V1.00\",
									\"version\":\"xxx\",
									\"userLabel\":\"xxx\",
									\"contentLocation\":\"xxx\"
									}";

	$conversion_response = json_decode($conversion_response_template, true);



	try {
		$conv_status = isset($json_request['converters']);

		if ($conv_status){
			if ($json_request['converters'] == 'listInstalledEngines' ) {
				$general_response = $engine_response;
			}
		}
	} catch (Exception $e) {
		// none
	}


// 2019-06-28  - running as .bat
	$op_string = operating_system_detection($debug, $fd_log);
	$op_string = strtolower($op_string);  // 1.7.04
		

	if ($json_request['action'] == 'conversion' ||  $json_request['action'] == 'data_extraction' || $json_request['action'] == 'svg_js_creation_cvheadless' || $json_request['action'] == 'svg_js_creation' || $json_request['action'] == 'svg_creation' || $json_request['action'] == 'pdf_creation' || $json_request['action'] == 'svg_creation_sharepoint_REST' ) {

		$converter = $json_request['converter'];
		$version = $json_request['version'];
		$contenttype = $json_request['contentType'];


		if (isset($json_request['contentLocation']))
			$contentlocation = $json_request['contentLocation'];
		if (isset($json_request['embeddedContent']))
			$embeddedcontent = $json_request['embeddedContent'];
		if (isset($json_request['contentStream']))
			$contentstream = $json_request['contentStream'];

		$contentresponse = $json_request['contentResponse'];

		if (isset($json_request['leaveStreamOnServer']))
			$remainOnServer = $json_request['leaveStreamOnServer'];


		$contentformat = $json_request['contentFormat'];
		$userlabel = $json_request['userLabel'];
		$parameters = $json_request['parameters'];


		$contentusername = $json_request['contentUsername'];
		$contentpassword = $json_request['contentPassword'];

		// make a loop over parameters, create the proper parameter format for exec()
		$param_string = "";
		$param_string_painter = "";

		$max = sizeof($parameters);
		$output_file_extension = "txt";



//if ($debug) echo "\$max: $max \n";

		if ($debug){
			fwrite($fd_log, "\$max: $max  \r\n");
		}


			
		
		
		for ($i = 0; $i < $max; $i++) {
			if ($parameters[$i]['paramName'] != ''){
				if ($parameters[$i]['paramValue'] != ''){
					// there is both a param and a value

					if (( $converter == 'xxLinkList-XML LL2014' && $parameters[$i]['paramName']== 'f' && $parameters[$i]['paramValue'] == 'xml')
					  || ($converter == 'xxLinkList-XML LL2014' && $parameters[$i]['paramName']== 'f' && $parameters[$i]['paramValue'] == 'XML')){

					  		// 2016-01-26  : assuming this issue fixed, therefore blocks this branch, will test this case
					  		// make this a general "sidebranch"


					  		// do nothing LinkList-XML 2014 cannot parse -f=xml  (bug in current converter release)
					  }
					  else{

						// here we check if for the f parameters in case of svg_js_creation_cvheadless
						if ($converter == 'AutoXchange AX2011' && $parameters[$i]['paramName']== 'f' && $json_request['action'] == 'svg_js_creation_cvheadless'){

							$param_string = $param_string . " -" . 'f' ."=". 'dwf';           // if svg_js_creation_cvheadless, then make a dwf conversion first, after that apply Painter

					  	}
					  	else{

							// here we check if for all other parameters in case of svg_js_creation_cvheadless
							if ($converter == 'AutoXchange AX2011' && $parameters[$i]['paramName']!= 'f' && $json_request['action'] == 'svg_js_creation_cvheadless'){

								if ($parameters[$i]['paramName']== 's' || $parameters[$i]['paramName']== 'lw'){

									$param_string_painter = $param_string_painter . " -" . $parameters[$i]['paramName'] ."=". $parameters[$i]['paramValue'];            ;

								}
								else{

									$param_string = $param_string . " -" . $parameters[$i]['paramName'] ."=". $parameters[$i]['paramValue'];            ;
								}

							}
							else{  // general case for creating the parameter string!!

								if ($parameters[$i]['paramName'] == 'xpath') $add_xpath = false;  // we are not using the config xpath, instead we use the one in the parameters
								if ($parameters[$i]['paramName'] == 'lpath') $add_lpath = false;  // we are not using the config xpath, instead we use the one in the parameters

								if ($parameters[$i]['paramName'] == 'layout'){
// 2019-06-28  - running as .bat
									if (strpos($op_string, 'win') !== false) {
										$param_string = $param_string . " \"-" . $parameters[$i]['paramName'] ."=". $parameters[$i]['paramValue'] ."\"";
									}
									else
										$param_string = $param_string . " -" . $parameters[$i]['paramName'] ."=\"". $parameters[$i]['paramValue'] ."\"";
								}
								else {
// 2019-06-28  - running as .bat
									if (strpos($op_string, 'win') !== false) {
										$param_string = $param_string . " \"-" . $parameters[$i]['paramName'] ."=". $parameters[$i]['paramValue'] ."\"";
									}
									else
										$param_string = $param_string . " -" . $parameters[$i]['paramName'] ."=\"". $parameters[$i]['paramValue'] ."\"";
									}
							}
							//	$param_string = $param_string . " -" . $parameters[$i]['paramName'] ."=". $parameters[$i]['paramValue'];            ;
					  	}
					  }
				}
				else{
					// there is only a param
					$param_string = $param_string . " -" . $parameters[$i]['paramName'] ;            ;
				}
			}
			else {
			// the parameter is empty, do nothing
			}

			// find the extension of the output file

			if ($parameters[$i]['paramName'] == 'f'){

				if ($converter == 'AutoXchange AX2011' && $parameters[$i]['paramName']== 'f' && $json_request['action'] == 'svg_js_creation_cvheadless'){
					$output_file_extension = 'dwf';       // we need to create a dwf file before passing over to Painter
				}
				else{
					$output_file_extension = $parameters[$i]['paramValue'];
				}
			}
		}

//if ($debug)	echo "\$param_string: $param_string\n";
		if ($debug){
			fwrite($fd_log, "\$param_string: $param_string  \r\n");
		}


 		$file_content = '';
		// make a random number and add the extension of the input file
		$temp_file_name = rand();
		$fullPath	 =  $fileLocation  . 'f' . $temp_file_name . '.' . strtolower($contentformat) ;

		// 1.5.12		
		if  ($json_request['action'] != 'svg_creation_sharepoint_REST' ){ 		
			// 1.5.12
			$server_load = 1;  // we are assuming server load
			if ($debug){
				fwrite($fd_log, "We are setting \$server_load:  $server_load  \r\n");
			}
			$pos = strpos($contentlocation, 'http');
			$pos_2 = strpos($contentlocation, $httpHost);
			if ($pos !== false) {
				if ($pos == 0){
					$server_load = 0;  // we are loading via http, we therefore need to input file to temp folder
				}
			}
			if ($pos_2 !== false) {
				if ($pos_2 == 0){
					$server_load = 1;  // we are on the same server, so we simply swap $httpHost for $home_dir
					$contentlocation = str_replace($httpHost, $home_dir, $contentlocation);	
				}
			}
			
			if ($debug){
				fwrite($fd_log, "after content location check \$server_load:  $server_load  , content-type: ($contenttype \r\n");
			}
			// 1.5.12 up
		}
		
		// we have two branches, standard file load branch
		if ($json_request['action'] != 'svg_creation_sharepoint_REST'){

					// fetch the input file   - dependent on where it originates
					if ($contenttype == 'file'){

						$pos = strpos($contentlocation, 'http');
						if ($pos !== false) {
							if ($pos == 0){
								$contentlocation = str_replace(' ', '%20', $contentlocation);
							}
						}

						if ($contentusername == '' || $contentpassword == ''  ){
							$file_content = "";
							try {
								$file_content = @file_get_contents($contentlocation);
							} catch (Exception $e) {
								// content is empty, AX will give an error code response
								$file_content = "";
							}
						}
						else{
							//echo "before context...";

							$context = stream_context_create(array (
								'http' => array (
								'header' => 'Authorization: Basic ' . base64_encode("$contentusername:$contentpassword")
								)
							));
							$data = file_get_contents($contentlocation, false, $context);

						}

					}

					if ($contenttype == 'embedded'){
						$file_content = base64_decode($embeddedcontent);
					}


					if ($contenttype == 'stream'){

						$pos = strpos($contentstream, 'http');
						if ($pos !== false) {
							if ($pos == 0){
								$contentstream = str_replace(' ', '%20', $contentstream);
							}
						}

						if ($contentusername == '' || $contentpassword == ''  ){


							$call_with_argument = $contentstream ."?userLabel=" . $userlabel;

							//echo " 1 here we call a stream:  $call_with_argument \n";

							$file_content = file_get_contents($call_with_argument, true);


						}
						else{

							$parts = explode("//", $contentstream);

							$call_with_argument = $parts[0] . "//" . $contentusername .":" . $contentpassword . "@" . $parts[1] ."?userLabel=" . $userlabel;

							//echo " 2 here we call a stream:  $call_with_argument \n";

							$file_content = file_get_contents($call_with_argument, true);

						}
					}


//			if ($debug)	 echo "\$fullPath: $fullPath \n";
		if ($debug){
			fwrite($fd_log, "\$fullPath: $fullPath  \r\n");
		}

			
		
		// 3.3.02c  - we only create the fullPath file, if not on server! 
		if ($server_load == 0){		
			if ($fd = fopen ($fullPath, "w+")) {
				fwrite($fd, $file_content);
				fclose ($fd);
					if ($debug){
						fwrite($fd_log, "in creating file  \r\n");
					}
			}
		}
		
//			if ($debug)	echo "\$fullPath: $fullPath\n";
		if ($debug){
			fwrite($fd_log, "\$fullPath: $fullPath  \r\n");
		}

		} // end of standard branch for file load and save into temp folder for conversion




		if ($debug){
			fwrite($fd_log, "AA: before setting \$fullPath:  \$server_load $server_load   \$contenttype $contenttype \r\n");
		}
		
		// 1.5.12
		if  ($json_request['action'] != 'svg_creation_sharepoint_REST' ){ 				
			// 1.5.12	  - if a file on server we load that, and use that path for xpath
			if ($server_load==1 &&  $contenttype == 'file'){
				$fullPath  = $contentlocation;
			}
		}

		
		if ($debug){
			fwrite($fd_log, "\$fullPath: $fullPath  \r\n");
		}
			
		
		

		// we have two branches, standard file load branch

		if ($json_request['action'] == 'svg_creation_sharepoint_REST'){

			//echo $contenttype ."  " . $contentlocation . "  ". $fullPath . " " . $Settings['Url'] . "  ";

			
			
			if ($contentusername!=""){
				if ($contenttype == 'file') cvjs_downloadSharepointFile($contentlocation, $fullPath , $Settings[$contentusername]['Url'], $Settings[$contentusername]['UserName'] , $Settings[$contentusername]['Password'], $Settings[$contentusername]['RESTApiUrl'], $Settings[$contentusername]['DrawingUrl']);
				if ($contenttype == 'stream') cvjs_downloadSharepointFile($contentlocation, $fullPath , $Settings[$contentusername]['Url'], $Settings[$contentusername]['UserName'] , $Settings[$contentusername]['Password'], $Settings[$contentusername]['RESTApiUrl'], $Settings[$contentusername]['DrawingUrl']);
			}
			else{
				if ($contenttype == 'file') cvjs_downloadSharepointFile($contentlocation, $fullPath , $Settings['Url'], $Settings['UserName'] , $Settings['Password'], $Settings['RESTApiUrl'], $Settings['DrawingUrl']);		
				if ($contenttype == 'stream') cvjs_downloadSharepointFile($contentstream, $fullPath , $Settings['Url'], $Settings['UserName'] , $Settings['Password'], $Settings['RESTApiUrl'], $Settings['DrawingUrl']);
			}
	
			//if (true) { echo " we abort"; return;}
		}


		// build the command line
		$command_line = "";


//if ($debug)	echo "\$converter_list: $converter_list\n";
		if ($debug){
//			fwrite($fd_log, "\$converter_list: $converter_list  \r\n");
		}

		// make a loop over converters, find the executable and location
		$engine_path = "";
		$max_conv = sizeof($converter_list);

//if ($debug)	 "\$max_conv: $max_conv\n";
		if ($debug){
			fwrite($fd_log, "\$max_conv: $max_conv  \r\n");
		}

		// Authentication - access right
		// if guest, then swap to DEMO conversion engine
		if ($demo_mode == TRUE){

			$converter = $converter . " DEMO";                          // here we possibly need a better redirection scheme
		}


			

		for ($i = 0; $i < $max_conv; $i++) {
			if ($converter_list[$i]['converter'] == $converter &&  $converter_list[$i]['version'] == $version){

				$engine_path = $converter_list[$i]['location'] . '/' . $converter_list[$i]['executable'];
				
// 2019-06-28  - running as .bat
				if (strpos($op_string, 'win') !== false) {
					fwrite($fd_log, "Inside Windows loop, updating engine_path " . operating_system_detection($debug, $fd_log)."    \r\n");
					$engine_path = $converter_list[$i]['location'] . '/run_ax2020.bat "' . $engine_path . '"';
				}
				

				if ($converter == 'AutoXchange AX2015')
					$ld_lib_path = $converterLocation . ':' . $converterLocation . 'ax2011' ;   // we use the lib location based on engine location
				else
					$ld_lib_path = $converterLocation;   // we use the lib location based on engine location
				
				
			}
			else {
			// do nothing
			}

		}


//if ($debug)	 "\$engine_path: $engine_path\n";
		if ($debug){
			fwrite($fd_log, "\$engine_path: $engine_path  \r\n");
		}

		$command_line =	$engine_path;


		// add input file

//		$command_line = $command_line . " -i=" . $home_dir . '/' . $fullPath;


// 2019-06-28  - running as .bat
		if (strpos($op_string, 'win') !== false) {
			$command_line = $command_line . " \"-i=" . $fullPath . "\"";
		}
		else
		$command_line = $command_line . " -i=\"" . $fullPath . "\"";

	
		// add output file

		$outputFile	 =  $fileLocation  . 'f' . $temp_file_name . '.' . $output_file_extension;



// 2019-06-28  - running as .bat
		if (strpos($op_string, 'win') !== false) {
			$command_line = $command_line . " \"-o=" . $outputFile . "\"";
		}
		else		
			$command_line = $command_line . " -o=\"" . $outputFile . "\"";


		// add parameters

		$command_line = $command_line . " " . $param_string;

		for ($i = 0; $i < $max_conv; $i++) {
			if ($converter_list[$i]['converter'] == $converter &&  $converter_list[$i]['version'] == $version){


				if ($add_lpath){
// 2019-06-28  - running as .bat
					if (strpos($op_string, 'win') !== false) {
						$command_line = $command_line . " \"-lpath=" . $licenseLocation . "\" "  ;
					}
					else		
						$command_line = $command_line . " -lpath=\"" . $licenseLocation . "\" "  ;

					//$command_line = $command_line . " -lpath=\"" . $licenseLocation . "\" "  ;
				}
// 2019-06-28  - running as .bat
				if ($add_xpath){
					if (strpos($op_string, 'win') !== false) {
						$command_line = $command_line  . " \"-xpath=" . $xpathLocation . "\" " ;
					}
					else		
						$command_line = $command_line  . " -xpath=\"" . $xpathLocation . "\" " ;

					//$command_line = $command_line  . " -xpath=\"" . $xpathLocation . "\" " ;    // we use the config xpath if no xpath is added through parameters
				}
			}
			else {
			// do nothing
			}
		}

		// we are not using xpath from the settings, but using the contentlocation
		
		if ($debug){
			fwrite($fd_log, "new \$add_xpath  ".$add_xpath ."  \r\n");
		}
		
		
		if (!$add_xpath){

			if ($debug){
				fwrite($fd_log, "In loop to add xpath  \r\n");
			}
			
			$command_line_parameter_xpath = false;
			for ($i = 0; $i < $max; $i++) {
				if ($parameters[$i]['paramName'] == 'xpath'){
					$command_line_parameter_xpath = true;
				}
			}


			if ($debug){
				fwrite($fd_log, "after first loop \$command_line_parameter_xpath $command_line_parameter_xpath  \r\n");
			}

			if (!$command_line_parameter_xpath){
				// strip off file name of $contentlocation		
				$pos1 = strrpos ( $contentlocation , "/");
				$xloc = substr($contentlocation, 0, $pos1+1);
				// use contentlocation as xpath
				//	$command_line = $command_line  . " -xpath=\"" . $xloc . "\" " ;    // content location is used as xpath

// 2019-06-28  - running as .bat
					if (strpos($op_string, 'win') !== false) {
						$command_line = $command_line  . " \"-xpath=" . $xloc . "\" " ;
					}
					else		
						$command_line = $command_line  . " -xpath=\"" . $xloc . "\" " ;
			}
		}
			
		
		if ($demo_mode == TRUE){

			$command_line = $command_line . " -demo";

		}



//if ($debug) echo "\$command_line: $command_line XXXXXXX\n";
		if ($debug){
			fwrite($fd_log, "\$command_line: $command_line XXXXXXX  \r\n");
		}




		 $saved = getenv("LD_LIBRARY_PATH");
		 if ($saved) { $ld_lib_path =  $ld_lib_path . ":$saved"; }           // append old paths if any

//if ($debug)	echo "library path before new env: $ld_lib_path \n";
		if ($debug){
			fwrite($fd_log, "library path before new env: $ld_lib_path  \r\n");
		}

		  $val_env = putenv("LD_LIBRARY_PATH=$ld_lib_path");              // set new value

//if ($debug)	echo "this is the value we got setting the environment $val_env \n";
		if ($debug){
			fwrite($fd_log, "this is the value we got setting the environment $val_env  \r\n");
		}


		$newenv = getenv("LD_LIBRARY_PATH");
//if ($debug)	echo "getenv after putenv: $newenv \n";
		if ($debug){
			fwrite($fd_log, "getenv after putenv: $newenv   \r\n");
		}


		if (strcmp($newenv, $saved) == 0) {

//			if ($debug)	echo "no change of environment, we therefore use apache parameter \n";
		if ($debug){
			fwrite($fd_log, "no change of environment, we therefore use apache parameter    \r\n");
		}

			apache_setenv('LD_LIBRARY_PATH', $ld_lib_path);

		    // there has been no change of environment, we therefore use
		}


		 $new_stuff = getenv("LD_LIBRARY_PATH");

//if ($debug)	echo "getenv after apache_setenv LD_LIBRARY_PATH=$new_stuff  \n";
//		if ($debug){
//			fwrite($fd, "getenv after apache_setenv LD_LIBRARY_PATH=$new_stuff    \r\n");
//		}





//		 $rep_sys = system($command_line, $retval);                    	// do system command;
//		echo "system= $rep_sys  return val = $retval \n";
//		 $rep_sys = system('ls -l', $retval);                    	// do system command;
//
//		echo "system= $rep_sys  return val = $retval \n";


		$out = array();

// we want to increase the execution time for time consuming conversions
// 4 min is the maximum
set_time_limit(240);



// if the content file is of type DWF and the process is svg_js_creation_cvheadless, then we do not call conversion
// we pass the input dwf file directly over to CV headless and painter

		if ( $json_request['action'] == 'svg_js_creation_cvheadless' && ( $contentformat == 'DWF' || $contentformat == 'dwf'  )){

			$cp_command_line = 'mv ./files/f' . $temp_file_name . '.' . $contentformat . ' ./files/f' . $temp_file_name . '.dwf' ;
			exec($cp_command_line, $out, $return1);

			// echo " \n rename the file to the converter output file \n";

		}
		else{  // call the converter

//if ($debug) echo " before call to exec:  " .$command_line;
//if ($debug) echo " before call to exec, engine_path:  " .$engine_path;
//if ($debug) echo ' current script owner: ' . get_current_user();
 //			chmod($engine_path, 0777);

 		if ($debug){
			fwrite($fd_log, "before call to exec:  " .$command_line ."    \r\n");
			fwrite($fd_log, "before call to exec, engine_path:  " .$engine_path."    \r\n");
			fwrite($fd_log, "current script owner: " . get_current_user()."    \r\n");
// 2019-06-28		
			fwrite($fd_log, "operating_system_detection(): " . operating_system_detection($debug, $fd_log)."    \r\n");
		}

		
		
		

			exec( $command_line, $out, $return1);


			
//  if ($debug) echo " exec out " .$out;
//  if ($debug) echo " exec return1 ". $return1;
			
 		if ($debug){
//			fwrite($fd_log, "exec out $out   \r\n");
			fwrite($fd_log, "exec return1  $return1   \r\n");
		}


//			$last_line = system($command_line, $retval);
//if ($debug) echo " system last_line " .$last_line;
//if ($debug) echo " system retval ". $retval;




			// clean out originating file + temp w2d file (if present)
			//$file_1 = $home_dir. '/' . $fullPath;
			$file_1 = $fullPath;


//if ($debug) echo "  unlink1 " . $file_1;
 		if ($debug){
			fwrite($fd_log, "unlink1  $file_1   \r\n");
		}

		// 3.3.02c - we only delete the source file, if not same server load
		if (!$debug && $server_load == 0)
			if (file_exists($file_1)){
				unlink($file_1);
			}

			$file_1 = $fileLocation  . 'f' . $temp_file_name . '_ac1024.dwg';
		if (!$debug)
			if (file_exists($file_1)){
				unlink($file_1);
			}

//if ($debug) echo " unlink2 " . $file_1;
 		if ($debug){
			fwrite($fd_log, "unlink2  $file_1   \r\n");
		}


	//echo  " \$output_file_extension = $output_file_extension \n";

			if ($output_file_extension == 'dwf' || $output_file_extension == 'DWF'){
				$file_2	 =  $home_dir . $fileLocation  . 'f' . $temp_file_name . '.w2d';

				if (file_exists($file_2)){
					unlink($file_2);
				}

				// here we need to make a loop an remove all w2d files if conversion is done with -vn=*ALL*

			}


		}



//		echo "return1= $return1 ";
//		echo "out= $out ";


		putenv("LD_LIBRARY_PATH=$saved");           	// restore old value
		$cont_loc = $httpHost . "/" . $outputFile ;


		$embed_cont = "";




// if svg_js_creation_cvheadless, take the dwf file, run it through painter,to produce three new .js files
// delete the dwf file after use.

		if ($json_request['action'] == 'svg_js_creation_cvheadless'){


// first create a new text based dwf file which has been through headlessCV -


// set up the call to headless CV

			$cv_codebase= "/home/cadviewer/tms-restful-api/cv_headless/";
			$cv_classpath = "./cv_headless";

			$headless_command_line = "sh ./cv_headless/callCVheadless.sh " .  $cv_codebase . " " .  $home_dir . $outputFile . " " . $home_dir . $fileLocation  . 'fj' . $temp_file_name . '.' . $output_file_extension;

// echo " ZZZ $headless_command_line ZZZZZ \n";

// echo		shell_exec($headless_command_line);

			shell_exec($headless_command_line);


// echo "  $headless_command_line \n";

// set up the call to painter

			$building_data_identifier = 'BUILDING_DATA_IDENTIFIER';
			$floor = 'FLOOR';

//   -ext parameter added

			$js_command_line = "./converters/painter_L64_01_32 " . $home_dir . $fileLocation  . 'fj' . $temp_file_name . '.' . $output_file_extension ." ". $param_string_painter ." -ext -bldg=" . $building_data_identifier ." -floor=" . $floor;


// echo "  $js_command_line \n";

			exec($js_command_line, $out, $return1);

// echo "  $return1 \n";
// echo "  $out \n";

			// rename $temp_file_name .js file to -full.js


			$cp_command_line = 'mv ./files/fj' . $temp_file_name . '.js ./files/fj' . $temp_file_name . '-full.js' ;

			exec($cp_command_line, $out, $return1);


			$rm_command_line = 'rm ./files/fj' . $temp_file_name . '.js';

			exec($rm_command_line, $out, $return1);


//echo "  $cp_command_line \n";
//echo "  $rm_command_line \n";

			// copy over base-DB.js into  $temp_file_name.js


			$fP1 = $fileLocation  .'/fj' . $temp_file_name . '.js';
			$baseDB = './cv_headless/base-DB.js';
			$file_content1 = file_get_contents($baseDB);

	//echo "  $file_content1 XXXX  \n";

			if ($fd1 = fopen ($fP1, "w")) {
				fwrite($fd1, $file_content1);
				fclose ($fd1);
			}


			$old_cont_file = $home_dir . $outputFile;

			if (file_exists($old_cont_file)){
				unlink($old_cont_file);
			}

			$old_cont_file = $home_dir . $fileLocation  . 'fj' . $temp_file_name . '.' . $output_file_extension;

			// we do not yet unlink the CV Headless DWF file, as both CV and Painter needs improvements
			if (file_exists($old_cont_file)){
//				unlink($old_cont_file);
			}

		}


	$return1 = 'E' . $return1;


// set up how the content is responded  , json_request['action'] of type conversion and data_extraction

		if ( $contentresponse == 'file'){
			$embed_cont = "";

		}

		if ( $contentresponse == 'embedded'){
			$cont_loc = "";
			$cont_file = $home_dir . $outputFile;
 			$file_content_conv = file_get_contents($cont_file);
			$embed_cont = base64_encode($file_content_conv);

			// unlink content file as it is now encoded into the response
			unlink($cont_file);

		}

		if ( $contentresponse == 'stream'){
			$embed_cont = "";
			$cont_loc = $stream_response_cgi . "?remainOnServer=" . $remainOnServer . "&fileTag=f" . $temp_file_name . "&Type=" . $output_file_extension;
		}



		// array with response - for type "file" and "stream" - below we rewrite it if of type "embedded"
		$conversion_response = array( "completedAction" => $json_request['action'],
									  "errorCode" => $return1,
									  "converter" => $converter,
									  "version" => $version,
									  "userLabel" => $userlabel,
									  "contentResponse" => $contentresponse,
									  "contentLocation" => $cont_loc);


		if ( $contentresponse == 'stream'){

			$conversion_response = array( "completedAction" => $json_request['action'],
										  "errorCode" => $return1,
										  "converter" => $converter,
										  "version" => $version,
										  "userLabel" => $userlabel,
										  "contentResponse" => $contentresponse,
										  "contentStream" => $cont_loc);
		}





		if ( $contentresponse == 'embedded'){

			$conversion_response = array( "completedAction" => $json_request['action'],
										  "errorCode" => $return1,
										  "converter" => $converter,
										  "version" => $version,
										  "userLabel" => $userlabel,
										  "contentResponse" => $contentresponse,
										  "embeddedContent" => $embed_cont);

		}






// set up how the content is responded  , json_request['action'] of type svg_js_creation_cvheadless
// based on CV headless

		if ( $json_request['action'] == 'svg_js_creation_cvheadless' ){


			if ( $contentresponse == 'file'){
				$embed_cont = "";
				$cont_loc1= $home_dir . $fileLocation  . 'fj' . $temp_file_name . '-full.js';
				$cont_loc2= $home_dir . $fileLocation  . 'fj' . $temp_file_name . '-nodes.js';
				$cont_loc3= $home_dir . $fileLocation  . 'fj' . $temp_file_name . '.js';
				$cont_loc4= $home_dir . $fileLocation  . 'fj' . $temp_file_name . '-thumb.js';
			}


			if ( $contentresponse == 'embedded'){
				$cont_file = $home_dir . $fileLocation  . 'fj' . $temp_file_name . '-full.js';
				$file_content_conv = file_get_contents($cont_file);
				$embed_cont1 = base64_encode($file_content_conv);
				unlink($cont_file);

				$cont_file = $home_dir . $fileLocation  . 'fj' . $temp_file_name . '-nodes.js';
				$file_content_conv = file_get_contents($cont_file);
				$embed_cont2 = base64_encode($file_content_conv);
				unlink($cont_file);

				$cont_file = $home_dir . $fileLocation  . 'fj' . $temp_file_name . '.js';
				$file_content_conv = file_get_contents($cont_file);
				$embed_cont3 = base64_encode($file_content_conv);
				unlink($cont_file);

				$cont_file = $home_dir . $fileLocation  . 'fj' . $temp_file_name . '-thumb.js';
				$file_content_conv = file_get_contents($cont_file);
				$embed_cont4 = base64_encode($file_content_conv);
				unlink($cont_file);



			}

			if ( $contentresponse == 'stream'){
				$embed_cont = "";
				$cont_loc1 = $stream_response_cgi . "?remainOnServer=" . $remainOnServer . "&fileTag=" . 'fj' . $temp_file_name . '-full' . "&Type=" . "js";

				$cont_loc2 = $stream_response_cgi . "?remainOnServer=" . $remainOnServer . "&fileTag=" . 'fj' . $temp_file_name . '-nodes' . "&Type=" . "js";

				$cont_loc3 = $stream_response_cgi . "?remainOnServer=" . $remainOnServer . "&fileTag=" . 'fj' . $temp_file_name . "&Type=" . "js";

				$cont_loc4 = $stream_response_cgi . "?remainOnServer=" . $remainOnServer . "&fileTag=" . 'fj' . $temp_file_name . '-thumb' . "&Type=" . "js";


			}



			// array with response - for type "file" and "stream" - below we rewrite it if of type "embedded"
			$conversion_response = array( "completedAction" => $json_request['action'],
										  "errorCode" => $return1,
										  "converter" => $converter,
										  "version" => $version,
										  "userLabel" => $userlabel,
										  "contentResponse" => $contentresponse,
										  "contentLocationGraphics" => $cont_loc1,
										  "contentLocationNodes" => $cont_loc2,
										  "contentLocationData" => $cont_loc3,
										  "contentLocationThumb" => $cont_loc4);


			if ( $contentresponse == 'stream'){

				$conversion_response = array( "completedAction" => $json_request['action'],
											  "errorCode" => $return1,
											  "converter" => $converter,
											  "version" => $version,
											  "userLabel" => $userlabel,
											  "contentResponse" => $contentresponse,
											  "contentStreamGraphics" => $cont_loc1,
											  "contentStreamNodes" => $cont_loc2,
											  "contentStreamData" => $cont_loc3,
											  "contentStreamThumb" => $cont_loc4);
			}




			if ( $contentresponse == 'embedded'){

				$conversion_response = array( "completedAction" => $json_request['action'],
											  "errorCode" => $return1,
											  "converter" => $converter,
											  "version" => $version,
											  "userLabel" => $userlabel,
											  "contentResponse" => $contentresponse,
											  "embeddedContentGraphics" => $embed_cont1,
											  "embeddedContentNodes" => $embed_cont2,
											  "embeddedContentData" => $embed_cont3,
											  "contentStreamThumb" => $embed_loc4);

			}


		}





// set up how the content is responded  , json_request['action'] of type svg_js_creation
// based on AX2017 -f=js conversion

		if ( $json_request['action'] == 'svg_js_creation' ){


			if ( $contentresponse == 'file'){
				$embed_cont = "";
				$cont_loc1= $fileLocationUrl  . 'f' . $temp_file_name . '-full.js';
				$cont_loc2= $fileLocationUrl  . 'f' . $temp_file_name . '-nodes.js';
				$cont_loc3= $fileLocationUrl  . 'f' . $temp_file_name . '.js';
				$cont_loc4= $fileLocationUrl  . 'f' . $temp_file_name . '-thumb.js';
			}


			if ( $contentresponse == 'embedded'){
				$cont_file = $fileLocation  . 'f' . $temp_file_name . '-full.js';
				$file_content_conv = file_get_contents($cont_file);
				$embed_cont1 = base64_encode($file_content_conv);
				unlink($cont_file);

				$cont_file = $fileLocation  . 'f' . $temp_file_name . '-nodes.js';
				$file_content_conv = file_get_contents($cont_file);
				$embed_cont2 = base64_encode($file_content_conv);
				unlink($cont_file);

				$cont_file = $fileLocation  . 'f' . $temp_file_name . '.js';
				$file_content_conv = file_get_contents($cont_file);
				$embed_cont3 = base64_encode($file_content_conv);
				unlink($cont_file);

				$cont_file = $fileLocation  . 'f' . $temp_file_name . '-thumb.js';
				$file_content_conv = file_get_contents($cont_file);
				$embed_cont4 = base64_encode($file_content_conv);
				unlink($cont_file);



			}

			if ( $contentresponse == 'stream'){
				$embed_cont = "";
				$cont_loc1 = $stream_response_cgi . "?remainOnServer=" . $remainOnServer . "&fileTag=" . 'f' . $temp_file_name . '-full' . "&Type=" . "js";

				$cont_loc2 = $stream_response_cgi . "?remainOnServer=" . $remainOnServer . "&fileTag=" . 'f' . $temp_file_name . '-nodes' . "&Type=" . "js";

				$cont_loc3 = $stream_response_cgi . "?remainOnServer=" . $remainOnServer . "&fileTag=" . 'f' . $temp_file_name . "&Type=" . "js";

				$cont_loc4 = $stream_response_cgi . "?remainOnServer=" . $remainOnServer . "&fileTag=" . 'f' . $temp_file_name . '-thumb' . "&Type=" . "js";


			}



			// array with response - for type "file" and "stream" - below we rewrite it if of type "embedded"
			$conversion_response = array( "completedAction" => $json_request['action'],
										  "errorCode" => $return1,
										  "converter" => $converter,
										  "version" => $version,
										  "userLabel" => $userlabel,
										  "contentResponse" => $contentresponse,
										  "contentLocationGraphics" => $cont_loc1,
										  "contentLocationNodes" => $cont_loc2,
										  "contentLocationData" => $cont_loc3,
										  "contentLocationThumb" => $cont_loc4);


			if ( $contentresponse == 'stream'){

				$conversion_response = array( "completedAction" => $json_request['action'],
											  "errorCode" => $return1,
											  "converter" => $converter,
											  "version" => $version,
											  "userLabel" => $userlabel,
											  "contentResponse" => $contentresponse,
											  "contentStreamGraphics" => $cont_loc1,
											  "contentStreamNodes" => $cont_loc2,
											  "contentStreamData" => $cont_loc3,
											  "contentStreamThumb" => $cont_loc4);
			}




			if ( $contentresponse == 'embedded'){

				$conversion_response = array( "completedAction" => $json_request['action'],
											  "errorCode" => $return1,
											  "converter" => $converter,
											  "version" => $version,
											  "userLabel" => $userlabel,
											  "contentResponse" => $contentresponse,
											  "embeddedContentGraphics" => $embed_cont1,
											  "embeddedContentNodes" => $embed_cont2,
											  "embeddedContentData" => $embed_cont3,
											  "contentStreamThumb" => $embed_loc4);

			}


		}

//if ($debug) echo $json_request['action'];
 		if ($debug){
			fwrite($fd_log, $json_request['action'] . "   \r\n");
		}



		if ( $json_request['action'] == 'svg_creation' || $json_request['action'] == 'pdf_creation' ){


			if ( $contentresponse == 'file'){
				$embed_cont = "";
				$cont_loc3= $fileLocationUrl  . 'f' . $temp_file_name . '.' . $output_file_extension;
			}


			if ( $contentresponse == 'embedded'){

				$cont_file = $fileLocation  . 'f' . $temp_file_name . '.' . $output_file_extension;
				$file_content_conv = file_get_contents($cont_file);
				$embed_cont3 = base64_encode($file_content_conv);
				unlink($cont_file);


			}

			if ( $contentresponse == 'stream'){
				$embed_cont = "";

				$cont_loc3 = $stream_response_cgi . "?remainOnServer=" . $remainOnServer . "&fileTag=" . 'f' . $temp_file_name . "&Type=" . $output_file_extension;


			}



			// array with response - for type "file" and "stream" - below we rewrite it if of type "embedded"
			$conversion_response = array( "completedAction" => $json_request['action'],
										  "errorCode" => $return1,
										  "converter" => $converter,
										  "version" => $version,
										  "userLabel" => $userlabel,
										  "contentLocation" => $contentlocation,
										  "contentLocationData" => $cont_loc3);


			if ( $contentresponse == 'stream'){

				$conversion_response = array( "completedAction" => $json_request['action'],
											  "errorCode" => $return1,
											  "converter" => $converter,
											  "version" => $version,
											  "userLabel" => $userlabel,
											  "contentLocation" => $contentlocation,
											  "contentResponse" => $contentresponse,
											  "contentStreamData" => $cont_loc3);
			}

			if ( $contentresponse == 'file'){

				$conversion_response = array( "completedAction" => $json_request['action'],
											  "errorCode" => $return1,
											  "converter" => $converter,
											  "version" => $version,
											  "userLabel" => $userlabel,
											  "contentLocation" => $contentlocation,
											  "contentResponse" => $contentresponse,
											  "contentStreamData" => $cont_loc3);
			}




			if ( $contentresponse == 'embedded'){

				$conversion_response = array( "completedAction" => $json_request['action'],
											  "errorCode" => $return1,
											  "converter" => $converter,
											  "version" => $version,
											  "userLabel" => $userlabel,
											  "contentLocation" => $contentLocation,
											  "contentResponse" => $contentresponse,
											  "embeddedContentData" => $embed_cont3);

			}


		}






		$conversion_resp = _json_encode($conversion_response);
		$general_response = $conversion_resp;

	}




	// Format data into a JSON response
	$json_response = json_encode($general_response);


	// SEND BACK RESPONSE!!!!!!

	// Deliver formatted data, if no errors and if authentication is correct
	if (($wrong_post_format == 0)  && ($authentication_success == TRUE)){

		//echo "XXX here";


		// echo $json_response;    - removed 2016-01-28
		// 2016-01-28  here we need to wrap the json encoded resonse into the callback method


		
		if ($debug){
			fwrite($fd_log, "\$json_response = $json_response  \r\n");
			if ($post_request_flag){
				fwrite($fd_log, "\$post_request_flag, \$json_response only  \r\n");
			}
			else{
				fwrite($fd_log, "\$post_request_flag false \$callback. = " . $callback.'(' . $json_response . ')' ."\r\n");
			}	
			fwrite($fd_log, "LAST BEFORE CALLBACK   \r\n \r\n \r\n");
			fclose($fd_log);	
		}

	
		// if jsonp then prepend callback, if standard post do not
		if ($post_request_flag){
			// Set HTTP Response Content Type
				header('Content-Type: application/json; charset=utf-8');
  			echo $json_response;
		}
		else
    		echo $callback.'(' . $json_response . ')';

		exit;
	}

	


	
	} catch(Exception $e) {
		echo "The exception $e was created on line: " . $e->getLine();
	}	
	


	// hereafter auxillary methods

	function _json_encode($val)
	{
	    if (is_string($val)) return '"'.addslashes($val).'"';
	    if (is_numeric($val)) return $val;
	    if ($val === null) return 'null';
	    if ($val === true) return 'true';
	    if ($val === false) return 'false';

	    $assoc = false;
	    $i = 0;
	    foreach ($val as $k=>$v){
	        if ($k !== $i++){
	            $assoc = true;
	            break;
	        }
	    }
	    $res = array();
	    foreach ($val as $k=>$v){
	        $v = _json_encode($v);
	        if ($assoc){
	            $k = '"'.addslashes($k).'"';
	            $v = $k.':'.$v;
	        }
	        $res[] = $v;
	    }
	    $res = implode(',', $res);
	    return ($assoc)? '{'.$res.'}' : '['.$res.']';
	}

	/* return Operating System */
	function operating_system_detection($debug, $fd_log){

	
		if ( isset( $_SERVER ) ) {
			$agent = $_SERVER['HTTP_USER_AGENT'];
		}
		else {
			global $HTTP_SERVER_VARS;
			if ( isset( $HTTP_SERVER_VARS ) ) {
				$agent = $HTTP_SERVER_VARS['HTTP_USER_AGENT'];
			}
			else {
				global $HTTP_USER_AGENT;
				$agent = $HTTP_USER_AGENT;
			}
		}
	
		if ($debug){
			fwrite($fd_log, "AGENT operating_system_detection():  \$agent = $agent  \r\n");
		}
		
		// THIS ONLY GETS THE AGENT FROM USER, WE NEED THE AGENT FROM SERVER
		
		
		$agent = PHP_OS;
		
		if ($debug){
			fwrite($fd_log, "AGENT PHP_OS : " . PHP_OS . "\r\n");
		}
		
		
		
		return trim ( $agent);
		
		/**
		
		$ros[] = array('Windows XP', 'Windows XP');
		$ros[] = array('Windows NT 5.1|Windows NT5.1)', 'Windows XP');
		$ros[] = array('Windows 2000', 'Windows 2000');
		$ros[] = array('Windows NT 5.0', 'Windows 2000');
		$ros[] = array('Windows NT 4.0|WinNT4.0', 'Windows NT');
		$ros[] = array('Windows NT 5.2', 'Windows Server 2003');
		$ros[] = array('Windows NT 6.0', 'Windows Vista');
		$ros[] = array('Windows NT 7.0', 'Windows 7');
		$ros[] = array('Windows CE', 'Windows CE');
		$ros[] = array('(media center pc).([0-9]{1,2}\.[0-9]{1,2})', 'Windows Media Center');
		$ros[] = array('(win)([0-9]{1,2}\.[0-9x]{1,2})', 'Windows');
		$ros[] = array('(win)([0-9]{2})', 'Windows');
		$ros[] = array('(windows)([0-9x]{2})', 'Windows');
		// Doesn't seem like these are necessary...not totally sure though..
		//$ros[] = array('(winnt)([0-9]{1,2}\.[0-9]{1,2}){0,1}', 'Windows NT');
		//$ros[] = array('(windows nt)(([0-9]{1,2}\.[0-9]{1,2}){0,1})', 'Windows NT'); // fix by bg
		$ros[] = array('Windows ME', 'Windows ME');
		$ros[] = array('Win 9x 4.90', 'Windows ME');
		$ros[] = array('Windows 98|Win98', 'Windows 98');
		$ros[] = array('Windows 95', 'Windows 95');
		$ros[] = array('(windows)([0-9]{1,2}\.[0-9]{1,2})', 'Windows');
		$ros[] = array('win32', 'Windows');
		$ros[] = array('(java)([0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,2})', 'Java');
		$ros[] = array('(Solaris)([0-9]{1,2}\.[0-9x]{1,2}){0,1}', 'Solaris');
		$ros[] = array('dos x86', 'DOS');
		$ros[] = array('unix', 'Unix');
		$ros[] = array('Mac OS X', 'Mac OS X');
		$ros[] = array('Mac_PowerPC', 'Macintosh PowerPC');
		$ros[] = array('(mac|Macintosh)', 'Mac OS');
		$ros[] = array('(sunos)([0-9]{1,2}\.[0-9]{1,2}){0,1}', 'SunOS');
		$ros[] = array('(beos)([0-9]{1,2}\.[0-9]{1,2}){0,1}', 'BeOS');
		$ros[] = array('(risc os)([0-9]{1,2}\.[0-9]{1,2})', 'RISC OS');
		$ros[] = array('os/2', 'OS/2');
		$ros[] = array('freebsd', 'FreeBSD');
		$ros[] = array('openbsd', 'OpenBSD');
		$ros[] = array('netbsd', 'NetBSD');
		$ros[] = array('irix', 'IRIX');
		$ros[] = array('plan9', 'Plan9');
		$ros[] = array('osf', 'OSF');
		$ros[] = array('aix', 'AIX');
		$ros[] = array('GNU Hurd', 'GNU Hurd');
		$ros[] = array('(fedora)', 'Linux - Fedora');
		$ros[] = array('(kubuntu)', 'Linux - Kubuntu');
		$ros[] = array('(ubuntu)', 'Linux - Ubuntu');
		$ros[] = array('(debian)', 'Linux - Debian');
		$ros[] = array('(CentOS)', 'Linux - CentOS');
		$ros[] = array('(Mandriva).([0-9]{1,3}(\.[0-9]{1,3})?(\.[0-9]{1,3})?)', 'Linux - Mandriva');
		$ros[] = array('(SUSE).([0-9]{1,3}(\.[0-9]{1,3})?(\.[0-9]{1,3})?)', 'Linux - SUSE');
		$ros[] = array('(Dropline)', 'Linux - Slackware (Dropline GNOME)');
		$ros[] = array('(ASPLinux)', 'Linux - ASPLinux');
		$ros[] = array('(Red Hat)', 'Linux - Red Hat');
		// Loads of Linux machines will be detected as unix.
		// Actually, all of the linux machines I've checked have the 'X11' in the User Agent.
		//$ros[] = array('X11', 'Unix');
		$ros[] = array('(linux)', 'Linux');
		$ros[] = array('(amigaos)([0-9]{1,2}\.[0-9]{1,2})', 'AmigaOS');
		$ros[] = array('amiga-aweb', 'AmigaOS');
		$ros[] = array('amiga', 'Amiga');
		$ros[] = array('AvantGo', 'PalmOS');
		//$ros[] = array('(Linux)([0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,3}(rel\.[0-9]{1,2}){0,1}-([0-9]{1,2}) i([0-9]{1})86){1}', 'Linux');
		//$ros[] = array('(Linux)([0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,3}(rel\.[0-9]{1,2}){0,1} i([0-9]{1}86)){1}', 'Linux');
		//$ros[] = array('(Linux)([0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,3}(rel\.[0-9]{1,2}){0,1})', 'Linux');
		$ros[] = array('[0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,3})', 'Linux');
		$ros[] = array('(webtv)/([0-9]{1,2}\.[0-9]{1,2})', 'WebTV');
		$ros[] = array('Dreamcast', 'Dreamcast OS');
		$ros[] = array('GetRight', 'Windows');
		$ros[] = array('go!zilla', 'Windows');
		$ros[] = array('gozilla', 'Windows');
		$ros[] = array('gulliver', 'Windows');
		$ros[] = array('ia archiver', 'Windows');
		$ros[] = array('NetPositive', 'Windows');
		$ros[] = array('mass downloader', 'Windows');
		$ros[] = array('microsoft', 'Windows');
		$ros[] = array('offline explorer', 'Windows');
		$ros[] = array('teleport', 'Windows');
		$ros[] = array('web downloader', 'Windows');
		$ros[] = array('webcapture', 'Windows');
		$ros[] = array('webcollage', 'Windows');
		$ros[] = array('webcopier', 'Windows');
		$ros[] = array('webstripper', 'Windows');
		$ros[] = array('webzip', 'Windows');
		$ros[] = array('wget', 'Windows');
		$ros[] = array('Java', 'Unknown');
		$ros[] = array('flashget', 'Windows');
		// delete next line if the script show not the right OS
		//$ros[] = array('(PHP)/([0-9]{1,2}.[0-9]{1,2})', 'PHP');
		$ros[] = array('MS FrontPage', 'Windows');
		$ros[] = array('(msproxy)/([0-9]{1,2}.[0-9]{1,2})', 'Windows');
		$ros[] = array('(msie)([0-9]{1,2}.[0-9]{1,2})', 'Windows');
		$ros[] = array('libwww-perl', 'Unix');
		$ros[] = array('UP.Browser', 'Windows CE');
		$ros[] = array('NetAnts', 'Windows');
		$file = count ( $ros );
		$os = '';
		
		for ( $n=0 ; $n<$file ; $n++ ){
			if ( preg_match('/'.$ros[$n][0].'/i' , $agent, $name)){
				$os = @$ros[$n][1].' '.@$name[2];
				break;
			}
		}
		return trim ( $os );
	
	**/
	
	}
?>