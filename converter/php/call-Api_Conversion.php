<?php
/*
    TMS RESTful API
 
    This script provides a RESTful API interface for web application accessing
    Tailor Made Software Conversion and Data Extraction engines

   Input:
        $_GET  = JSON (jsonp)formatted request according to TMS REST Api specification
		alternatively the content directly posted to the RESTful API, JSON formatted,

    Output: A formatted JSON HTTP response according to TME REST Api specification	
	
	
	
*/

	$scriptversion = "9.18.3";

	// 8.71.1  - we make flag for nextcloud
	$nextcloud = true;
	$nextcloud_xpath_flag = false;
	$nextcloud_xpath = "";



	// Configuration file for CADViewer Community and CADViewer Enterprise version and standard settings
	require 'CADViewer_config.php';


	// respond to preflights
	if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
		// return only the headers and not the content
		// only allow CORS if we're doing a GET - i.e. no saving for now.
		$http_origin = '';
		if (isset($_SERVER['HTTP_ORIGIN'])) {
		$http_origin = $_SERVER['HTTP_ORIGIN'];
		}
		elseif (isset($_SERVER['HTTP_REFERER'])) {
		$http_origin = $_SERVER['HTTP_REFERER'];
		}
		
		if ($checkorigin){
			
			if (in_array($http_origin, $allowed_domains))
			{
				header("Access-Control-Allow-Origin: $http_origin");
				header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
				header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
			}	
		
		}
		else{
		
			header("Access-Control-Allow-Origin: *");
			header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
			header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
		}
		exit;
	}


	$http_origin = '';

	if (isset($_SERVER['HTTP_ORIGIN'])) {
	$http_origin = $_SERVER['HTTP_ORIGIN'];
	}
	elseif (isset($_SERVER['HTTP_REFERER'])) {
	$http_origin = $_SERVER['HTTP_REFERER'];
	}

	if ($checkorigin){
		
		if (in_array($http_origin, $allowed_domains))
		{
			header("Access-Control-Allow-Origin: $http_origin");
			header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
			header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
		}	
	}
	else{
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
	}

	// Configuration file for CADViewer Enterprise specific settings - Note!: CADViewer_config.php must be set!
	//require 'CADViewer_Enterprise_config.php';

	// only oncomment this, if loading drawing files from a sharepoint server via REST calls
	// require 'CV-JS_sharepoint_connector.php';

	// Settings of variables, not defined in configuration files
	$add_xpath = false;  	// NOTE: We are overwriting the configuration file settings with the contentlocation setting. 		
	$add_lpath = true;  	// we are adding lpath from configuration file
	$add_fpath = true;
	$remainOnServer = 1;  	// general flag to tell pickup stream to to leave on server



	//  try-catch  1.5.09
	try {

	// we are setting the callback identifier to match the one defined in the originating document
	// 2016-01-28
	// create debug file
	if ($debug) {
		if ($fd_log = fopen ("call-Api_Conversion_log.txt", "w")) {
			fwrite($fd_log, "\r\n NEW-FILE-CONVERSION-BEGIN   $scriptversion  \r\n \r\n");
			fwrite($fd_log, "Opening call-Api_Conversion_log.txt for new conversion: \r\n");
		}		
	}
	
	if ($debug){
		//echo "before _ isset()x2 ";
		fwrite($fd_log, "before _ isset()x2  \r\n");
	}	
						
	$post_request_flag = false;
    $callback ='tms_restful_api';
	$json_data = "";


	if ($jsonp_flag){
		
		// 7.1.8 - parameter controlled

		if(!empty($_GET['request']))
		{
	//		if ($debug)	echo "_GET['tms_restful_api'] is set ";
			if ($debug){
				fwrite($fd_log, "_GET['request'] is set \r\n");
			}
			$request = $_GET['request'];
			//$json_data = $_GET['json'];
			$json_data = urldecode ( $request );
			//echo $json_data;
			if (empty($json_data)){
				$wrong_post_format = 1;
				$error_response = "{\"completedAction\":\"none\",\"errorCode\":\" \POST variable $_POST[request] is not defined \"}";
				//echo $error_response;
				// 2016-01-28 changed to be wrapped in return method
				echo $callback.'(' . json_encode($error_response) . ')';
				exit;
			}
			echo $json_data;
			exit;
		}
	
	
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
	

	
	} // end jsonp_flag


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
		if ($debug){
			fwrite($fd_log, "\$json_data: $json_data   \r\n");
		}

	// parse the _POST call / content retrieved from file_get_content

	$json_request = json_decode($json_data, true);


	
	if ($jsonp_flag)  // 7.1.17   - we control post from the config file
		if (empty($json_request)){

		$wrong_post_format = 1;
		$error_response = "{\"completedAction\":\"none\",\"errorCode\":\" JSON string expression incorrectly formatted \"}";
		//echo $error_response;
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
			fwrite($fd_log, "exception deletefolderstatus=$e   \r\n");
		// none
	}


	$temp_var = "";
	try {
		$conv_status = isset($json_request['converters']);
		if ($conv_status) $temp_var = $json_request['converters'];
	} catch (Exception $e) {
		fwrite($fd_log, "converters=$e   \r\n");
	}

	if ($debug){
		fwrite($fd_log, "\$json_request(conv): $temp_var,   \r\n");
	}



	// 7.1.17   - we control post from the config file
	if (!$jsonp_flag){

		// set the general post reponse flag already implemented
		$post_request_flag = true;
		$wrong_post_format = 0;

		fwrite($fd_log, "\$temp_var is null:$temp_var, this must be a post call, also:\$jsonp_flag=$jsonp_flag, isset:". isset($_POST['request'])  );
		if(isset($_POST['request'])){
			fwrite($fd_log, " request=". $_POST['request']."<end request>");
//			$json_data = json_encode($_POST['request']);
			$json_data = $_POST['request'];
			$json_request = json_decode($json_data, true);
			fwrite($fd_log, "\$json_data isset, requestconverter:". $json_request['converter'] ." \r\n");
		}
		else{
			fwrite($fd_log, " request=". $_POST['request']."<end request>");
		}
	}

	$general_response= "{ \"error\": [] }";

//  list over available engines
	$engine_response="{\"installedEngines\":[{\"converter\":\"dwg2SVG\",\"version\":\"V1.00\",\"status\":\"active\"}, {\"converter\":\"AutoXchange AX2020\",\"version\":\"V1.00\",\"status\":\"active\"}, {\"converter\":\"AutoXchange AX2022\",\"version\":\"V2.00\",\"status\":\"active\"}, {\"converter\":\"LinkList 2022\",\"version\":\"V2.00\",\"status\":\"active\"}]}";

	$engine_listing = [
		"internalListInstalledEngines" => [
			[
				"converter" => "dwg2SVG",
				"version" => "V1.00",
				"executable" => $community_executable,
				"location" => $converterLocation,
				"status" => "active"
			],
			[
				"converter" => "AutoXchange AX2020",
				"version" => "V1.00",
				"executable" => $ax2023_executable,
				"location" => $converterLocation,
				"status" => "active"
			],
			[
				"converter" => "AutoXchange AX2022",
				"version" => "V2.00",
				"executable" => $ax2023_executable,
				"location" => $converterLocation,
				"status" => "active"
			],
			[
				"converter" => "AutoXchange AX2020 DEMO",
				"version" => "V1.00",
				"executable" => $ax2023_executable,
				"location" => $converterLocation,
				"status" => "active"
			],
			[
				"converter" => "LinkList 2020",
				"version" => "V2.00",
				"executable" => $linklist2023_executable,
				"location" => $linklistLocation,
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

	$op_string = operating_system_detection($debug, $fd_log);
	$op_string = strtolower($op_string);  // 1.7.04
		

// GENERAL VARIABLES	

	$converter = $json_request['converter'];
	$version = $json_request['version'];
	$max_conv = sizeof($converter_list);
	$engine_path = "";
	$userlabel = $json_request['userLabel'];


	// 8.10.1

	$fileStamp = "";

//// THESE ARE HANDLING RELATED TO AUTOXCHANGE 2023 
	if ($json_request['action'] == 'conversion' ||  $json_request['action'] == 'data_extraction' || $json_request['action'] == 'svg_js_creation_cvheadless' || $json_request['action'] == 'svg_js_creation' || $json_request['action'] == 'svg_creation' || $json_request['action'] == 'pdf_creation' || $json_request['action'] == 'svg_creation_sharepoint_REST' ) {

		$contenttype = $json_request['contentType'];

		if (isset($json_request['contentLocation']))
			$contentlocation = $json_request['contentLocation'];

		// 8.20.1
		if (isset($json_request['fileStamp']))
			$fileStamp = $json_request['fileStamp'];


		// 7.1.7
		fwrite($fd_log, "\$contentlocation:  $contentlocation  \r\n");
		$contentlocation = str_replace("%3A", ":", $contentlocation);	
		$contentlocation = str_replace("%2F", "/", $contentlocation);	
		fwrite($fd_log, "\$contentlocation:  $contentlocation  \r\n");

		// 7.9.14
		$contentlocation = urldecode($contentlocation);	


		if (isset($json_request['embeddedContent']))
			$embeddedcontent = $json_request['embeddedContent'];
		if (isset($json_request['contentStream']))
			$contentstream = $json_request['contentStream'];

		$contentresponse = $json_request['contentResponse'];

		if (isset($json_request['leaveStreamOnServer']))
			$remainOnServer = $json_request['leaveStreamOnServer'];

//		$remainOnServer = 1;

		$contentformat = $json_request['contentFormat'];
		$userlabel = $json_request['userLabel'];
		$parameters = $json_request['parameters'];


		$contentusername = $json_request['contentUsername'];
		$contentpassword = $json_request['contentPassword'];

		// make a loop over parameters, create the proper parameter format for exec()
		$param_string = "";
		$param_string_painter = "";

		$max = sizeof($parameters);
		$output_file_extension = "svg";


		if ($debug){
			fwrite($fd_log, "output_file_extension -init: $output_file_extension  \r\n");
		}



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
									$param_string_painter = $param_string_painter . " -" . $parameters[$i]['paramName'] ."=". $parameters[$i]['paramValue'];           
								}
								else{
									$param_string = $param_string . " -" . $parameters[$i]['paramName'] ."=". $parameters[$i]['paramValue'];           
								}
							}
							else{  // general case for creating the parameter string!!
								if ($parameters[$i]['paramName'] == 'xpath') $add_xpath = false;  // we are not using the config xpath, instead we use the one in the parameters
								if ($parameters[$i]['paramName'] == 'lpath') $add_lpath = false;  // we are not using the config xpath, instead we use the one in the parameters
								if ($parameters[$i]['paramName'] == 'fpath') $add_fpath = false;  // we are not using the config xpath, instead we use the one in the parameters

								$v1 = $parameters[$i]['paramName'];
								$v2 = $parameters[$i]['paramValue'];
								if ($debug){
									fwrite($fd_log, "param $v1 : $v2  \r\n");
								}
								if ( $v1 =='f' && $v2 == 'svg' && $svgz_compress == true){
									$parameters[$i]['paramValue'] = "svgz";
								}


								// 8.26.4
								if ($parameters[$i]['paramName'] == 'compare'){

									$compare_location = $parameters[$i]['paramValue'];
									$pos_2 = strpos($compare_location, $httpHost);
									

									if ($debug){
										fwrite($fd_log, " compare_location $compare_location   pos_2 $pos_2  \r\n");
									}
							


									// we do not need to download external http for now
									if ($pos_2 !== false) {
										if ($pos_2 == 0){
//											$server_load = 1;  // we are on the same server, so we simply swap $httpHost for $home_dir
											$compare_location = str_replace($httpHost, $home_dir ."/", $compare_location);	
										}
									}


									if ($debug){
										fwrite($fd_log, " compare_location $compare_location   pos_2 $pos_2  \r\n");
									}



									if (strpos($op_string, 'win') !== false) { // 2019-06-28  - running as .bat
										$param_string = $param_string . " \"-" . $parameters[$i]['paramName'] ."=". $compare_location ."\"";
									}
									else{
										$param_string = $param_string . " -" . $parameters[$i]['paramName'] ."=\"". $compare_location ."\"";
									}
								

								}
								else{
									if ($parameters[$i]['paramName'] == 'layout'){
										if (strpos($op_string, 'win') !== false) { // 2019-06-28  - running as .bat
											$param_string = $param_string . " \"-" . $parameters[$i]['paramName'] ."=". $parameters[$i]['paramValue'] ."\"";
										}
										else
											$param_string = $param_string . " -" . $parameters[$i]['paramName'] ."=\"". $parameters[$i]['paramValue'] ."\"";
									}
									else {
										if (strpos($op_string, 'win') !== false) { // 2019-06-28  - running as .bat
											$param_string = $param_string . " \"-" . $parameters[$i]['paramName'] ."=". $parameters[$i]['paramValue'] ."\"";
										}
										else
											$param_string = $param_string . " -" . $parameters[$i]['paramName'] ."=\"". $parameters[$i]['paramValue'] ."\"";
									}
	
								}
					
							}
							//	$param_string = $param_string . " -" . $parameters[$i]['paramName'] ."=". $parameters[$i]['paramValue'];            ;
					  	}
					  }
				}
				else{
					// there is only a param
					$param_string = $param_string . " -" . $parameters[$i]['paramName'] ;    
					

					if ($debug){
						fwrite($fd_log, "single param case: -init: $param_string  \r\n");
					}
			

					// if LinkLIst and -json, we change format to json, txt is default
					if ($parameters[$i]['paramName'] =='json' && strrpos($converter , "LinkList")==0){  // 6.5.20
						$output_file_extension = "json";
					}

				}
			}
			else {
			// the parameter is empty, do nothing
			}


			if ($debug){
				fwrite($fd_log, "check the param name for file exentsion: -init:" . $parameters[$i]['paramName'] . "  \r\n");
			}


			// find the extension of the output file
			if ($parameters[$i]['paramName'] == 'f'){
				if ($converter == 'AutoXchange AX2011' && $parameters[$i]['paramName']== 'f' && $json_request['action'] == 'svg_js_creation_cvheadless'){
					$output_file_extension = 'dwf';       // we need to create a dwf file before passing over to Painter
				}
				else{
					$output_file_extension = $parameters[$i]['paramValue'];
				}


				if ($debug){
					fwrite($fd_log, "output_file_extension: $output_file_extension  \r\n");
				}
	
			}
		}

		if ($debug){
			fwrite($fd_log, "  \$output_file_extension: $output_file_extension   \$param_string: $param_string  \r\n");
		}

 		$file_content = '';
		
		$temp_file_name = rand();
		$fullPath	 =  $fileLocation  . 'f' . $temp_file_name . '.' . strtolower($contentformat) ;


		// 8.37.1
		$svginputfile = 0;
		if (($json_request['contentFormat'] == 'SVG') || ($json_request['contentFormat'] == 'svg') || ($json_request['contentFormat'] == 'svgz') || ($json_request['contentFormat'] == 'SVGZ') ){
			$svginputfile = 1;
		}



		if ($debug){
			fwrite($fd_log, "  \$svginputfile: $svginputfile  \r\n");
		}



		// 6.5.20
		$newload = false;



		// 1.5.12		
		if  ($json_request['action'] != 'svg_creation_sharepoint_REST' ){ 		
			// 1.5.12
			$server_load = 1;  // we are assuming server load
			if ($debug){
				fwrite($fd_log, "We are setting \$server_load:  $server_load   $contentlocation \r\n");
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
					$contentlocation = str_replace($httpHost, $home_dir ."/", $contentlocation);	
				}
			}
			
			// nextcloud  8.71.0
			// if german or unicode characters on .dgn files we copy over the file to temp folder
			// we do a copy for all files!!!
			if ($nextcloud == true){
				$server_load = 0;

				// if DGN only!!!
				$lowercontentlocation = strtolower($contentlocation);
				$nextcloud_xpath_flag = false;


				if (strpos($lowercontentlocation,".dgn") == false) {
					$nextcloud_xpath_flag = false;	
				}else{
					// german characters in foldername, then do this!!!!
					if (strpos($lowercontentlocation,"ä") != false) {
						$nextcloud_xpath_flag = true;	
					}
					if (strpos($lowercontentlocation,"ö") != false) {
						$nextcloud_xpath_flag = true;	
					}
					if (strpos($lowercontentlocation,"ü") != false) {
						$nextcloud_xpath_flag = true;	
					}
					if (strpos($lowercontentlocation,"ẞ") != false) {
						$nextcloud_xpath_flag = true;	
					}



					if (strpos($lowercontentlocation,"æ") != false) {
						$nextcloud_xpath_flag = true;	
					}
					if (strpos($lowercontentlocation,"ø") != false) {
						$nextcloud_xpath_flag = true;	
					}

					if (strpos($lowercontentlocation,"å") != false) {
						$nextcloud_xpath_flag = true;	
					}


					///
					if (strpos($lowercontentlocation,"é") != false) {
						$nextcloud_xpath_flag = true;	
					}
					if (strpos($lowercontentlocation,"à") != false) {
						$nextcloud_xpath_flag = true;	
					}
					if (strpos($lowercontentlocation,"è") != false) {
						$nextcloud_xpath_flag = true;	
					}
					if (strpos($lowercontentlocation,"ì") != false) {
						$nextcloud_xpath_flag = true;	
					}
					if (strpos($lowercontentlocation,"ò") != false) {
						$nextcloud_xpath_flag = true;	
					}
					if (strpos($lowercontentlocation,"ù") != false) {
						$nextcloud_xpath_flag = true;	
					}
					if (strpos($lowercontentlocation,"â") != false) {
						$nextcloud_xpath_flag = true;	
					}
					if (strpos($lowercontentlocation,"ê") != false) {
						$nextcloud_xpath_flag = true;	
					}
					if (strpos($lowercontentlocation,"î") != false) {
						$nextcloud_xpath_flag = true;	
					}
					if (strpos($lowercontentlocation,"ô") != false) {
						$nextcloud_xpath_flag = true;	
					}
					if (strpos($lowercontentlocation,"û") != false) {
						$nextcloud_xpath_flag = true;	
					}
					if (strpos($lowercontentlocation,"ç") != false) {
						$nextcloud_xpath_flag = true;	
					}
					if (strpos($lowercontentlocation,"ë") != false) {
						$nextcloud_xpath_flag = true;	
					}
					if (strpos($lowercontentlocation,"ï") != false) {
						$nextcloud_xpath_flag = true;	
					}
					if (strpos($lowercontentlocation,"ü") != false) {
						$nextcloud_xpath_flag = true;	
					}

				}

				// this is for testing only!
				//$nextcloud_xpath_flag = true;

				if ($nextcloud_xpath_flag){

					$findex = strrpos($contentlocation, "/");
					$folderpath = substr($contentlocation, 0, $findex);
	
					// Open the folder			   
					$dir_handle = @opendir($folderpath);  // or die("Unable to open $path");

					
					if ($debug) {
						fwrite($fd_log, "NEXTCLOUD LOOP: folder handle  $dir_handle  \r\n");
					}		

					if ($dir_handle!=false){

						$subfolder = rand();
						$nextcloud_xpath = $fileLocation . '/xrefs/'. $subfolder . "/";
						mkdir($nextcloud_xpath, 0777, true);
	
						
						if ($debug) {
							fwrite($fd_log, "NEXTCLOUD LOOP: folder  $nextcloud_xpath  \r\n");
						}		
	
	
						// Loop through the files
						while ($file = readdir($dir_handle)) {
		
							if($file == "." || $file == "..")
								continue;

							// it must contain a . for a dgn file	
							if(strpos($file,".") == false) continue;

							$pathfile = $folderpath . '/' . $file;	
							if (file_exists($pathfile)){
								$cflag = copy($pathfile , $nextcloud_xpath . $file);

								if ($debug) {
									fwrite($fd_log, "FILE=$pathfile exists OK=$cflag \r\n");
								}		
							}
							else{
	
								if ($debug) {
									fwrite($fd_log, "FILE $pathfile DOES NOT EXIST \r\n");
								}		
							}
						}
	
					}


				}
			}
			// 8.71.1

			if ($debug){
				fwrite($fd_log, "after content location check \$server_load:  $server_load  , content-type: ($contenttype \r\n");
			}
			// 1.5.12 up
		}

		
		if ($debug){
			fwrite($fd_log, " location X004  contentlocation:$contentlocation \r\n");
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

						if ($debug){
							fwrite($fd_log, "X005 In action: contentype file: $contentlocation  \r\n");
						}

						if ($contentusername == '' || $contentpassword == ''  ){

							if ($debug){
								fwrite($fd_log, "before download file  \r\n");
							}



							try{   // 9.18.2
								if ($debug){
									fwrite($fd_log, " fullpath: $fullPath  \r\n");
									fwrite($fd_log, " contentlocation: $contentlocation \r\n");
								}
								$newfname = $fullPath;

								// php does an automatic urldecode, so this will remove the +
								//$contentlocation = urldecode($contentlocation);

								if ($debug){
									fwrite($fd_log, "urldecode contentlocation: $contentlocation \r\n ");
									fwrite($fd_log, "newfname: $newfname \r\n ");
								
								}


								// 9.18.2
								
								copy($contentlocation, $newfname);



								/*

								$file = fopen ($contentlocation, 'rb');
								if ($file) {
									$newf = fopen ($newfname, 'wb');
									if ($newf) {
										while(!feof($file)) {
											fwrite($newf, fread($file, 1024 * 8), 1024 * 8);
										}
									}
								}
								
								
								if ($file) {
									if ($debug) 
										fwrite($fd_log, "closing contenlocation after fopen and read \r\n ");
									fclose($file);
								}
								else{
									if ($debug) 
										fwrite($fd_log, "we could not close contenlocation after fopen and read \r\n ");						
								}
								
								
								
								if ($newf) {
									if ($debug) 
										fwrite($fd_log, "closing newfname after fopen and write \r\n");
									fclose($newf);
								}
								else{
									if ($debug) 
										fwrite($fd_log, "we could not close newfname after fopen and write \r\n ");								
								}
						
								*/
						
								// we use download function above
								$newload = true;


							}
							catch(Exception $err){
						
						
								if ($debug){
									fwrite($fd_log, "downloadFile: $err  \r\n");
								}
						
						
							}
						
							/*   THIS METHOD DOES NOT WORK FOR LARGE FILES

							$file_content = "";
							try {
								$file_content = @file_get_contents($contentlocation);
							} catch (Exception $e) {


								if ($debug){
									fwrite($fd_log, "no username/password file_get_contents: $e  \r\n");
								}
						
								// content is empty, AX will give an error code response
								$file_content = "";
							}

							*/



						}
						else{
							//echo "before context...";
							try {
								$context = stream_context_create(array (
									'http' => array (
									'header' => 'Authorization: Basic ' . base64_encode("$contentusername:$contentpassword")
									)
								));
								$data = file_get_contents($contentlocation, false, $context);

							} catch (Exception $e) {


								if ($debug){
									fwrite($fd_log, "username/password file_get_contents: $e  \r\n");
								}
						
							}

							if ($debug){
								fwrite($fd_log, "no username/password file_get_contents: $e  \r\n");
							}
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
		if ($server_load == 0 && !$newload){		
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
				if (strpos($op_string, 'win') !== false && $windowsbatprocessing) {
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

	//	$command_line =	$engine_path;     
		$command_line =	"\"" .  $engine_path . "\"";    // 6.5.20

		// add input file
//		$command_line = $command_line . " -i=" . $home_dir . '/' . $fullPath;
		if (strpos($op_string, 'win') !== false) {  // 2019-06-28  - running as .bat
			$command_line = $command_line . " \"-i=" . $fullPath . "\"";
		}
		else
		$command_line = $command_line . " -i=\"" . $fullPath . "\"";
	
		
		if ($json_request['action'] == 'pdf_creation'){
			
			if (strrpos($contentlocation, '/')>0) $fname = substr($contentlocation, strrpos($contentlocation, '/')+1,  strlen($contentlocation));
			if ($debug){
				fwrite($fd_log, "pdf_creaton outfile \$fname:  $fname  \r\n");
			}
			if (strrpos($fname, '\\')>0) $fname = substr($fname, strrpos($fname, '\\'),  strlen($fname) );
			if ($debug){
				fwrite($fd_log, "pdf_creaton outfile \$fname:  $fname  \r\n");
			}
			if (strrpos($fname, '.')>0)$fname = substr($fname, 0, strrpos($fname, '.'));
			if ($debug){
				fwrite($fd_log, "pdf_creaton outfile \$fname:  $fname  \r\n");
			}
			$subfolder = rand();
			mkdir($fileLocation . 'pdf/'. $subfolder, 0777, true);

			$temp_file_name = 'pdf/' . $subfolder . '/' . $fname;
			$outputFile	 =  $fileLocation  . $temp_file_name . '.' . $output_file_extension;

			if ($debug){
				fwrite($fd_log, "pdf_creaton outfile \$$outputFile	:  $outputFile	h  \r\n");
			}
		}
		else{
			$outputFile	 =  $fileLocation  . 'f' . $temp_file_name . '.' . $output_file_extension;
		}
			

		if (strpos($op_string, 'win') !== false) { // 2019-06-28  - running as .bat
			$command_line = $command_line . " \"-o=" . $outputFile . "\"";
		}
		else		
			$command_line = $command_line . " -o=\"" . $outputFile . "\"";
		
		// add parameters
		$command_line = $command_line . " " . $param_string;

		for ($i = 0; $i < $max_conv; $i++) {
			if ($converter_list[$i]['converter'] == $converter &&  $converter_list[$i]['version'] == $version){
				if ($add_fpath){ 
					// 2022-12-20  - running as .bat
					if (strpos($op_string, 'win') !== false) {
						$command_line = $command_line . " \"-fpath=" . $fontLocation . "\" "  ;
					}
					else		
						$command_line = $command_line . " -fpath=\"" . $fontLocation . "\" "  ;
					//$command_line = $command_line . " -lpath=\"" . $licenseLocation . "\" "  ;
				}
				if ($add_lpath){ // 2019-06-28  - running as .bat
					if (strpos($op_string, 'win') !== false) {
						$command_line = $command_line . " \"-lpath=" . $licenseLocation . "\" "  ;
					}
					else		
						$command_line = $command_line . " -lpath=\"" . $licenseLocation . "\" "  ;
					//$command_line = $command_line . " -lpath=\"" . $licenseLocation . "\" "  ;
				}
				if ($add_xpath){ // 2019-06-28  - running as .bat
					if (strpos($op_string, 'win') !== false) {

						if ($nextcloud_xpath_flag == 1){
							$command_line = $command_line  . " \"-xpath=" . $nextcloud_xpath . "\" " ;
						}
						else{
							$command_line = $command_line  . " \"-xpath=" . $xpathLocation . "\" " ;
						}

					}
					else{

						if ($nextcloud_xpath_flag == 1){
							$command_line = $command_line  . " -xpath=\"" . $nextcloud_xpath . "\" " ;
						}
						else
							$command_line = $command_line  . " -xpath=\"" . $xpathLocation . "\" " ;

					}	
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
			
			$command_line_parameter_xpath = 0;
			for ($i = 0; $i < $max; $i++) {
				if ($parameters[$i]['paramName'] == 'xpath'){
					$command_line_parameter_xpath = 1;
				}
			}


			if ($debug){
				fwrite($fd_log, "after first loop \$command_line_parameter_xpath: $command_line_parameter_xpath  \r\n");
			}

			$axconv = strrpos($converter , "AutoXchange");

			fwrite($fd_log, "after first loop \$command_line_parameter_xpath: $command_line_parameter_xpath  converter $converter   axconv $axconv \r\n");



//			if (($command_line_parameter_xpath == 0)   &&  (>0)){  
			if (($command_line_parameter_xpath == 0)  &&  ($axconv == 0)){  
					// strip off file name of $contentlocation		
				$pos1 = strrpos ( $contentlocation , "/");
				$xloc = substr($contentlocation, 0, $pos1+1);
				// use contentlocation as xpath
				//	$command_line = $command_line  . " -xpath=\"" . $xloc . "\" " ;    // content location is used as xpath

				fwrite($fd_log, "adding xpath: nc-flag:" . $nextcloud_xpath_flag . "  op_string:  " . $op_string . " nc_path: " .$nextcloud_xpath . "\n\r");

// 2019-06-28  - running as .bat
					if (strpos($op_string, 'win') !== false) {
						if ($nextcloud_xpath_flag == 1){
							$command_line = $command_line  . " \"-xpath=" . $nextcloud_xpath . "\" " ;
						}
						else{
							$command_line = $command_line  . " \"-xpath=" . $xloc . "\" " ;
						}
					}
					else{

						if ($nextcloud_xpath_flag == 1){
							$command_line = $command_line  . " -xpath=\"" . $nextcloud_xpath . "\" " ;
						}
						else
							$command_line = $command_line  . " -xpath=\"" . $xloc . "\" " ;

					}		
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
			fwrite($fd_log, "9.18.2 remove this check - no change of environment, we therefore use apache parameter    \r\n");
		}

//			apache_setenv('LD_LIBRARY_PATH', $ld_lib_path);

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




	if ($debug){
		fwrite($fd_log, "before call to exec:  " .$command_line ."    \r\n");
		fwrite($fd_log, "before call to exec, engine_path:  " .$engine_path."    \r\n");
		fwrite($fd_log, "current script owner: " . get_current_user()."    \r\n");
		fwrite($fd_log, "operating_system_detection(): " . operating_system_detection($debug, $fd_log)."    \r\n");
	}

	//$jsontemplate = '{"fullfilepath": "full file path","filename": "filename","filecreatestamp": "date1","filechangestamp": "2023-01-31T08:30:25.597Z","cachedfiles": [{ "fileName": "F1.svgz", "filecreatestamp": "datef1", "parameters": [{"paramName": "f", "paramValue": "svg"}]}]}';

	$jsontemplate = '{\"filename\": \"filename\",\"filecreatestamp\": \"date1\",\"filechangestamp\": \"2023-01-31T08:30:25.597Z\",\"cachedfiles\": [{ \"fullfilepath\": \"full file path\", \"fileName\": \"F1.svgz\", \"filecreatestamp\": \"datef1\", \"parameters\": [{\"paramName\": \"f\", \"paramValue\": \"svg\"}]}]}';


	/*
						{
							"filename": "filename",
							"filecreatestamp": "date1",
							"filechangestamp": "2023-01-31T08:30:25.597Z",
							"cachedfiles": [{
									"fullfilepath": "full file path",
									"fileName": "F1- 533271c6c7ec58eda68a0f882.svgz",
									"filecreatestamp": "datef1",
									"parameters": [{
										"paramName": "f",
										"paramValue": "svg"
									}, {
										"paramName": "ak",
										"paramValue": "FWK_1"
									}, {
										"paramName": "ia",
										"paramValue": ""
									}, {
										"paramName": "extents",
										"paramValue": ""
									}, {
										"paramName": "layout",
										"paramValue": "Sheet1"
									}]
								},
								{
									"fullfilepath": "full file path",
									"fileName": "Fab55b0ffbf5497e533271c6c7ec58eda68a0f882.svgz",
									"filecreatestamp": "2023-01-31T08:30:25.597Z",
									"parameters": [{
										"paramName": "f",
										"paramValue": "svg"
									}, {
										"paramName": "ak",
										"paramValue": "FWK_DEVICE_TYPE"
									}, {
										"paramName": "ia",
										"paramValue": ""
									}, {
										"paramName": "extents",
										"paramValue": ""
									}, {
										"paramName": "last",
										"paramValue": ""
									}]
								}
							]
						}

	*/
 


	$temp_file_name_org ="";
	$this_conversion_cached = false;

	fwrite($fd_log, "BEFORE CACHED CONVERSION t:$this_conversion_cached c: $cached_conversion" .($svginputfile==0) ."  $svginputfile    \n\r ");

	// 8.37.1
	if ($cached_conversion && ($svginputfile==0)){

		// we always leave the file on server if cached!!!!
		$remainOnServer = 1;

		fwrite($fd_log, "CACHED CONVERSION IMPLEMENT  $jsontemplate \n\r ");

		// 8.19.3  8.19.2  8.20.1
		$pos1 = strrpos ( $contentlocation , "/");
		$filenamej = substr($contentlocation, $pos1+1);

        $jsonfilename = $fileLocation . $filenamej . ".json";

		fwrite($fd_log, "FILENAME:" . $jsonfilename . " \n\r");


		// 8.53.1
		$current_fullfilepath =  substr($contentlocation, 0, $pos1);



		// copy the code from NODEJS
        $jsonfilename = $fileLocation . $filenamej . ".json";

		// here we make wsure we get the reference file in
		//$jsonfilename = "/xampp/htdocs/cadviewer_6_4//converters/files/reference-cache-file.json";

		// load the json file
		
		$jarray = "";
		$paramflags = [false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false];
		$cachecontents = "";

		fwrite($fd_log, "file exists: $jsonfilename " . file_exists($jsonfilename). "XXX\n\r" );

		if (file_exists($jsonfilename)){

			if ($fd = fopen ($jsonfilename, "rb")) {
				while(!feof($fd)) {
					$cachecontents .= fread($fd, 8192);
				}
				fclose ($fd);
				$jarray = json_decode($cachecontents, true);	
				// $this_conversion_cached = true;
	
				if ($jarray == null) {
					fwrite($fd_log, "no good XXX\n\r" );

					// if cannot open file IS NULL, we have to build the JSONstructure
					$this_conversion_cached = false;
					// add all content to jarray with content from this file
					$jarray = json_decode($jsontemplate, true);	
					//		$jarray = $jsontemplate;	
								//if (is_array($jarray))  fwrite($fd_log, "jarray is an array! XXX \n\r" );
					//fwrite($fd_log, "first attempt: " . $jarray['fullfilepath'] . " XXX \n\r" );
					//$jarray['fullfilepath']  =  substr($contentlocation, 0, $pos1);
					
					$jarray['filename']  =  $filenamej;
					$jarray['filecreatestamp']  = date_format(date_timestamp_set(new DateTime(), time()), 'c'); //"2023-01-31T08:30:25.597Z";  // current time stamp
					$pos2 = strrpos ( $outputFile , "/");
					$filenameout = substr($contentlocation, $pos1+1);
					$jarray['cachedfiles'][0]['fullfilepath']	= $current_fullfilepath;  // 8.54.1
					$jarray['cachedfiles'][0]['fileName'] = "f" . $temp_file_name . "." . $output_file_extension;  // current timestampt
					$jarray['cachedfiles'][0]['filecreatestamp'] = date_format(date_timestamp_set(new DateTime(), time()), 'c');// "file exists but not correct json";  // current timestampt
					$max = sizeof($parameters);
					fwrite($fd_log, "parameters size: " . $max . "  XXX "); // . $jarray['cachedfiles'][0]['parameters'][0]['paramName'] . "\n\r" );
					for ($i = 0; $i < $max; $i++) {
						$jarray['cachedfiles'][0]['parameters'][$i]['paramName'] = $parameters[$i]['paramName'];
						$jarray['cachedfiles'][0]['parameters'][$i]['paramValue'] = $parameters[$i]['paramValue'];
					}

					fwrite($fd_log, "jarray new file: " . json_encode($jarray) . " XXX \n\r" );
					fwrite($fd_log, " single entry fullfilepath cached 0: " . $jarray['cachedfiles'][0]['fullfilepath'] . " \n\r ");


				
				}
				else{
					fwrite($fd_log, "cashedfile likely, array wellformed, current-fullpath: $current_fullfilepath cached fullpath (in each cache):" . "   \n\r" );


					/* 8.54.1 - we strip out

					// if  not in same folder
					if ($current_fullfilepath != $jarray['fullfilepath']){
						// change the fullfilepath , remove cached files
						// trigger new conversion
						$jarray['fullfilepath'] = $current_fullfilepath;
						$this_conversion_cached = false;

						$jarray['cachedfiles'] = [];

						/*
						//$fsize = sizeof($jarray['cachedfiles']);
						for ($i = 0; $i < $fsize; $i++) {
							//$jarray['cachedfiles'][$i] = {};
						}
						* /

					}
					else{   // 8.53.1

					*/
					

						$fsize = sizeof($jarray['cachedfiles']);
						fwrite($fd_log, "number of conversion: " . $fsize . "XXX\n\r" );
			
						$continuecheckfiles = true;
						$foldercheck = false;
			
						for ($i = 0; $i < $fsize; $i++) {
			
							fwrite($fd_log, "folder of current file: " . $jarray['cachedfiles'][$i]['fullfilepath'] . "XXX\n\r" );
							
							if ($continuecheckfiles){
	
								if ($jarray['cachedfiles'][$i]['fullfilepath'] == $current_fullfilepath){
									$foldercheck = true;
								}
								else
									$foldercheck = false;

								fwrite($fd_log, "before file: " . $i . "  " . $jarray['cachedfiles'][$i]['fileName'] . "XXX\n\r" . strpos($jarray['cachedfiles'][$i]['fileName'], 'fphp'));
	
								$pos_3 = strpos($jarray['cachedfiles'][$i]['fileName'], 'fpdf');									
								if ($pos_3 !== false) {
									if ($pos_3 == 0){
	//											$server_load = 1;  // we are on the same server, so we simply swap $httpHost for $home_dir
										//$compare_location = str_replace($httpHost, $home_dir ."/", $compare_location);	
										$jarray['cachedfiles'][$i]['fileName'] = substr($jarray['cachedfiles'][$i]['fileName'], 1);
									}
								}
	
								fwrite($fd_log, "file: " . $i . "  " . $jarray['cachedfiles'][$i]['fileName'] . "XXX\n\r" );
								
								$max = sizeof($parameters);
								$paramflags = [false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false];
								for ($j = 0; $j < $max; $j++) {
									$maxk = sizeof($jarray['cachedfiles'][$i]['parameters']);
									for ($k = 0; $k < $maxk; $k++) {
										if ($jarray['cachedfiles'][$i]['parameters'][$k]['paramName'] ==  $parameters[$j]['paramName'] 
										&& $jarray['cachedfiles'][$i]['parameters'][$k]['paramValue'] == $parameters[$j]['paramValue']){
											$paramflags[$j] = true;
											fwrite($fd_log, "param match: " . $j . "  " . $parameters[$j]['paramName'] . " " . $parameters[$j]['paramValue'] . " XXX\n\r" );
										};
									}
								}
				
								// check if param OK
								// check if number of params OK
								// check if date OK
					
			
								// NOTE $paramcheck3  == is the DATE CHECK!   - to be done!
	
								fwrite($fd_log, " timeStamp= " . $fileStamp . "  jarray['filecreatestamp']= ". $jarray['filecreatestamp'] . "\n\r" );
										
			
	
								fwrite($fd_log, " timeStamp= " . strtotime($fileStamp) . "  jarray['filecreatestamp']= ". strtotime($jarray['filecreatestamp']) . "\n\r" );
	
								$paramcheck3 = false;
	
								if ($fileStamp =="none"){
									$paramcheck3 = true;   // no time check, we pass through
								}
								else{   // if the time sent down to CV, is newer than the time on server then we must convert
									if (strtotime($fileStamp)>strtotime($jarray['filecreatestamp'])){
										$paramcheck3 = false;   // we MUST! convert
									}
									else{
										$paramcheck3 = true;  // we pass through
									}
	
								}
	
	
								$paramcheck1 = ($max == $maxk);
								$paramcheck2 = true;
					
								if ($paramcheck1){
									for ($l = 0; $l < $maxk; $l++) {
										if (!$paramflags[$l]) $paramcheck2 = false;
									}
								}
					
								if ($paramcheck1 && $paramcheck2 && $paramcheck3 && $foldercheck){  // 8.54.1
									$this_conversion_cached = true;
									// update the temp name
	
									$temp_file_name_org = $temp_file_name;
	
									$temp_file_name = $jarray['cachedfiles'][$i]['fileName'];
									// teemp file name is substring
			
									// type is the type
									$poso = strrpos ( $temp_file_name , ".");
									$output_file_extension = substr($temp_file_name, $poso+1);
									$temp_file_name = substr($temp_file_name, 0, $poso);
									fwrite($fd_log, "temp_file_name= " . $temp_file_name . "  XXX output_file_extension= ". $output_file_extension . "\n\r" );
									$remainOnServer = 1;
									$continuecheckfiles = false;
								}
				
								//$this_conversion_cached = false;
								//$output_file_extension = "svg";
							}
			
						}
		
	/*  8.54.1
					}   // 8.53.1
*/
				}
	
			
				//if ok then set flag to abort conversion, but make new return JSON
				//if OK set flag to not continue loop.
	
				if (!$this_conversion_cached){
					// if NOT OK, then we need make a new entry into the JSON file, and trigger conversion
	
					$fsize = sizeof($jarray['cachedfiles']);
	
					$filenameout = substr($contentlocation, $pos1+1);
					$jarray['cachedfiles'][$fsize]['fullfilepath'] = $current_fullfilepath;
					$jarray['cachedfiles'][$fsize]['fileName'] = "f" . $temp_file_name . "." . $output_file_extension ;  
					$jarray['cachedfiles'][$fsize]['filecreatestamp'] = date_format(date_timestamp_set(new DateTime(), time()), 'c'); //"not cached but exists";  // current timestampt
					$max = sizeof($parameters);
					//fwrite($fd_log, "parameters size: " . $max . "  XXX ". $jarray['cachedfiles'][0]['parameters'][0]['paramName'] . "\n\r" );
					for ($i = 0; $i < $max; $i++) {
						$jarray['cachedfiles'][$fsize]['parameters'][$i]['paramName'] = $parameters[$i]['paramName'];
						$jarray['cachedfiles'][$fsize]['parameters'][$i]['paramValue'] = $parameters[$i]['paramValue'];
					}
		
				}
	
	
			}


		}
		else{
			// if cannot open file, we have to build the JSONstructure
			$this_conversion_cached = false;
			// add all content to jarray with content from this file
			$jarray = json_decode($jsontemplate, true);	
			//		$jarray = $jsontemplate;	
						//if (is_array($jarray))  fwrite($fd_log, "jarray is an array! XXX \n\r" );
			fwrite($fd_log, "CANNOT OPEN FILE, NEED TO BUILD JSON STRUCTURE  XXX \n\r" );
			//$jarray['fullfilepath']  =  substr($contentlocation, 0, $pos1);
			$jarray['filename']  =  $filenamej;
			$jarray['filecreatestamp']  = date_format(date_timestamp_set(new DateTime(), time()), 'c'); //"2023-01-31T08:30:25.597Z";  // current time stamp
			$pos2 = strrpos ( $outputFile , "/");
			$filenameout = substr($contentlocation, $pos1+1);
			$jarray['cachedfiles'][0]['fullfilepath'] = $current_fullfilepath; // 8.54.1
			$jarray['cachedfiles'][0]['fileName'] = "f" . $temp_file_name . "." . $output_file_extension ;  // 
			$jarray['cachedfiles'][0]['filecreatestamp'] = date_format(date_timestamp_set(new DateTime(), time()), 'c'); //"cannot open file";  // current timestampt
			$max = sizeof($parameters);
			fwrite($fd_log, "parameters size: " . $max . "  XXX "); // . $jarray['cachedfiles'][0]['parameters'][0]['paramName'] . "\n\r" );
			for ($i = 0; $i < $max; $i++) {
				$jarray['cachedfiles'][0]['parameters'][$i]['paramName'] = $parameters[$i]['paramName'];
				$jarray['cachedfiles'][0]['parameters'][$i]['paramValue'] = $parameters[$i]['paramValue'];
			}

			fwrite($fd_log, "jarray new file: " . json_encode($jarray) . " XXX \n\r" );
			fwrite($fd_log, " single entry fullfilepath in cache: " . $jarray['cachedfiles'][0]['fullfilepath'] . " \n\r ");

		}
		// check against the incoming json
		// if new conversion, make a new json / update json
		// if already cached, then skip conversion and update of json
		// we have to make the temp file the temp file from the json file

		if (!$this_conversion_cached){

			// here we save the JSON to the server
			file_put_contents($jsonfilename,  json_encode($jarray));

		}


	}




 
	$return1 = "";
	if ($this_conversion_cached){

		fwrite($fd_log, "Do nothing! we are just loading in the old temp svgz....");

	}
	else{


		// 8.37.1
		if ($svginputfile == 1){
			// this is an svg we do not convert!!!!
		} 
		else{
    
			exec( $command_line, $out, $return1);
			if ($debug){
				fwrite($fd_log, "exec return1  $return1   \r\n");

				fwrite($fd_log, "output log:  \r\n");

				// 8.70.5  out is an array
				foreach ($out as $value) {
					fwrite($fd_log, "$value  \r\n");
				}

			}

		}
	}
		

	if ($return1 < -128 || $return1>256)
		$return1 = 0;	

	if ($debug){
		fwrite($fd_log, "exec return1 check  $return1   \r\n");
	}


	//9.18.2
   if (!$debug)
	if (true){

		$pose = strrpos ( $contentlocation , ".");
		$exte = substr($contentlocation, $pose+1);
		$file_1 = $fileLocation  . 'f' . $temp_file_name_org . '.' . $exte;
		if (file_exists($file_1) && $svginputfile == 0){  // 8.37.1
			unlink($file_1);
			if ($debug){
				fwrite($fd_log, "unlink2  $file_1   \r\n");
			}
		}


		$file_1 = $fileLocation  . 'f' . $temp_file_name . '.' . $exte;
		if (file_exists($file_1) && $svginputfile == 0){   // 8.37.1
			unlink($file_1);
			if ($debug){
				fwrite($fd_log, "unlink2  $file_1   \r\n");
			}
		}


		$file_1 = $fileLocation  . 'f' . $temp_file_name . '.csv';
		if (file_exists($file_1) && $svginputfile == 0){   // 8.70.2
			unlink($file_1);
			if ($debug){
				fwrite($fd_log, "unlink2  $file_1   \r\n");
			}
		}




		// nextcloud   8.70.2
		if ($nextcloud_xpath_flag == 1){

			deleteDir($nextcloud_xpath);			
		}

		
	}


	// 8.37.2  - we have to make the output format the same as the originating file
	if ($svginputfile == 1){
		$output_file_extension = strtolower($contentformat);
	}





//if ($debug) echo " unlink2 " . $file_1;


//echo  " \$output_file_extension = $output_file_extension \n";

		if ($output_file_extension == 'dwf' || $output_file_extension == 'DWF'){
			$file_2	 =  $home_dir . $fileLocation  . 'f' . $temp_file_name . '.w2d';

			if (file_exists($file_2)){
				unlink($file_2);
			}
			// here we need to make a loop an remove all w2d files if conversion is done with -vn=*ALL*
		}

//		echo "return1= $return1 ";
//		echo "out= $out ";

		putenv("LD_LIBRARY_PATH=$saved");           	// restore old value
		$cont_loc = $httpHost . "/" . $outputFile ;

		$embed_cont = "";


	if ($return1 == "" ) $return1 = 0;

	$return1 = 'E' . $return1;

// set up how the content is responded  , json_request['action'] of type conversion and data_extraction

if ($debug){
	fwrite($fd_log, "Q1 contentresponse  $contentresponse   \r\n");
}




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

			if ($this_conversion_cached){   // 8.19.3{
				$cont_loc = $stream_response_cgi . "?remainOnServer=" . $remainOnServer . "&fileTag=" . $temp_file_name . "&Type=" . $output_file_extension;
			}
			else{
				$cont_loc = $stream_response_cgi . "?remainOnServer=" . $remainOnServer . "&fileTag=f" . $temp_file_name . "&Type=" . $output_file_extension;
			}

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
										  "contentStreamData" => $cont_loc);
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



//if ($debug) echo $json_request['action'];
 		if ($debug){
			fwrite($fd_log,"Q2:" . $json_request['action'] . "   \r\n");
		}

		if ( $json_request['action'] == 'svg_creation' || $json_request['action'] == 'pdf_creation' ){

			if ( $contentresponse == 'file'){
				$embed_cont = "";
				$cont_loc3= $fileLocationUrl  . $temp_file_name . '.' . $output_file_extension;
			}


			if ( $contentresponse == 'embedded'){

				$cont_file = $fileLocation  . 'f' . $temp_file_name . '.' . $output_file_extension;
				$file_content_conv = file_get_contents($cont_file);
				$embed_cont3 = base64_encode($file_content_conv);
				unlink($cont_file);


			}

			if ( $contentresponse == 'stream'){
				$embed_cont = "";

				if ($this_conversion_cached)  { // 8.19.3
					$cont_loc3 = $stream_response_cgi . "?remainOnServer=" . $remainOnServer . "&fileTag=" . $temp_file_name . "&Type=" . $output_file_extension;
				}
				else{
					$cont_loc3 = $stream_response_cgi . "?remainOnServer=" . $remainOnServer . "&fileTag=" . 'f' . $temp_file_name . "&Type=" . $output_file_extension;
				}

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


		//if ($debug){
		//	fwrite($fd_log,"Q3: \$conversion_response" . $conversion_response . "   \r\n");
		//}



		$conversion_resp = _json_encode($conversion_response);
		$general_response = $conversion_resp;


		if ($debug){
			fwrite($fd_log,"Q4: \$general_response" . $general_response . "   \r\n");
		}

	}

	// Format data into a JSON response
	$json_response = json_encode($general_response);


	if ($debug){
		fwrite($fd_log,"Q5: $wrong_post_format  $authentication_success  \$json_response" . $json_response . "   \r\n");
	}

	// SEND BACK RESPONSE!!!!!!

	// Deliver formatted data, if no errors and if authentication is correct
	if (($wrong_post_format == 0)  && ($authentication_success == TRUE)){

		//echo "XXX here";


		// echo $json_response;    - removed 2016-01-28
		// 2016-01-28  here we need to wrap the json encoded resonse into the callback method


		
		if ($debug){
			fwrite($fd_log, "\$json_response = $json_response  \r\n");
			if ($post_request_flag){
				fwrite($fd_log, "\$post_request_flag=$post_request_flag \$json_response only  \r\n");
			}
			else{
				fwrite($fd_log, "\$post_request_flag  \$callback. = " . $callback.'(' . $json_response . ')' ."\r\n");
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



//  download file from HTTP/HTTPS

function _downloadFile($url, $path, $fd_log)
{
	try{

		if ($debug){
			fwrite($fd_log, " $url  $path  \r\n");
		}




		$newfname = $path;
		$file = fopen ($url, 'rb');
		if ($file) {
			$newf = fopen ($newfname, 'wb');
			if ($newf) {
				while(!feof($file)) {
					fwrite($newf, fread($file, 1024 * 8), 1024 * 8);
				}
			}
		}
		if ($file) {
			fclose($file);
		}
		if ($newf) {
			fclose($newf);
		}


	}
	catch(Exception $err){


		if ($debug){
			fwrite($fd_log, "downloadFile: $err  \r\n");
		}


	}
}


function deleteDir($dirPath)
{
    if (!is_dir($dirPath)) {
        if (file_exists($dirPath) !== false) {
            unlink($dirPath);
        }
        return;
    }

    if ($dirPath[strlen($dirPath) - 1] != '/') {
        $dirPath .= '/';
    }

    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            deleteDir($file);
        } else {
            unlink($file);
        }
    }

    rmdir($dirPath);
}




?>
