<?php
namespace OCA\Cadviewer\Controller;

use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\JSONResponse;
use OCP\IL10N;
use OCP\ILogger;
use OCP\IRequest;
use OCP\IURLGenerator;


use OCA\Cadviewer\AppConfig;

class SettingsController extends Controller {

    private $trans;
    private $logger;
    private $urlGenerator;

    /**
     * Application configuration
     *
     * @var AppConfig
     */
    private $config;

    public function __construct($AppName,
                                    IRequest $request,
                                    IURLGenerator $urlGenerator,
                                    IL10N $trans,
                                    ILogger $logger,
                                    AppConfig $config
                                    ) {
        parent::__construct($AppName, $request);

        $this->urlGenerator = $urlGenerator;
        $this->trans = $trans;
        $this->logger = $logger;
        $this->config = $config;
    }

    /**
     * Check if licence is present in folder converters elseway get it from appConfig
     */
    public function checkIfLicenceIsPresent() {

        // Construct path to converter folder
        $currentpath = __FILE__;
        $home_dir = explode("/cadviewer/", __FILE__)[0]."/cadviewer/converter";

        // include CADViewer config for be able to acces to the location of ax2024 executable file
        require($home_dir."/php/CADViewer_config.php");

        $axlic_file = $licenseLocation."axlic.key";

        // Check if axlic_file exist
        if (file_exists($axlic_file)) {
            return;
        }
        
        
        $axlic_content = $this->config->GetAxlicLicenceKey();
        // check if is not empty
        if (empty($axlic_content)) {
            return;
        }
        // write content in axlic file
        file_put_contents($axlic_file, $axlic_content);
    }


    /**
     * Check if licence js is present in folder converters elseway get it from appConfig
     */
    public function checkIfLicenceJsIsPresent() {

        // Construct path to converter folder
        $currentpath = __FILE__;
        $home_dir = explode("/cadviewer/", __FILE__)[0]."/cadviewer/converter";

        // include CADViewer config for be able to acces to the location of ax2024 executable file
        require($home_dir."/php/CADViewer_config.php");

        $licence_key_file = $licenseLocation."cvlicense.js";

        if (file_exists($licence_key_file)) {
            return;
        }


        $licence_key_content = $this->config->GetLicenceKey();
        // check if is not empty
        if (empty($licence_key_content)) {
            return;
        }
        // write content in axlic file
        file_put_contents($licence_key_file, $licence_key_content);
    }

    /**
     * Save shx file in the fonts folder 
     */
    public function saveShxFile() {
        $file = $_FILES['file'];

        // Check for errors
        if ($file['error'] > 0) {
            // Handle the error
            return new JSONResponse(array(), Http::STATUS_BAD_REQUEST);
        } else {

            // Construct path to converter folder
            $currentpath = __FILE__;
            $home_dir = explode("/cadviewer/", __FILE__)[0]."/cadviewer/converter";

            // include CADViewer config for be able to acces to the location of ax2024 executable file
            require($home_dir."/php/CADViewer_config.php");

            $file_name = $file['name'];

            $shx_file = $converterLocation."fonts/".$file_name;

            // Process the file
            $tmp_name = $file['tmp_name'];

            // Saving it to a directory
            $res = move_uploaded_file($tmp_name, $shx_file);

            return new JSONResponse(array(), Http::STATUS_CREATED);
        }
    }

    /** 
     * Save axlic file in the converters folder 
    */
    public function saveAxlicFile() {
        $file = $_FILES['file'];

        // Check for errors
        if ($file['error'] > 0) {
            // Handle the error
            return new JSONResponse(array(), Http::STATUS_BAD_REQUEST);
        } else {

            // Construct path to converter folder
            $currentpath = __FILE__;
            $home_dir = explode("/cadviewer/", __FILE__)[0]."/cadviewer/converter";

            // include CADViewer config for be able to acces to the location of ax2024 executable file
            require($home_dir."/php/CADViewer_config.php");

            $output_detail = shell_exec($converterLocation.$ax2023_executable." -verify_detail");
            
            // extract information from key verification detail
            $lines = explode("\n", $output_detail);
            $number_of_users = -1;
            if (strpos($output_detail, "License Validated") !== false) {
                if (isset($lines[0]) && strpos($lines[0], "days until your") !== false) {
                    $number_of_users = intval($lines[3]);
                } else {
                    $number_of_users = intval($lines[2]);
                }
            }

            $axlic_file = $licenseLocation."axlic.key";

            // Process the file
            $tmp_name = $file['tmp_name'];

            // Saving it to a directory
            move_uploaded_file($tmp_name, $axlic_file);
            $axlic_file_content = file_get_contents($axlic_file);
            $this->config->SetAxlicLicenceKey($axlic_file_content);
            $this->flushCache();
            return new JSONResponse(array("users" => $this->config->GetUsers($number_of_users)), Http::STATUS_CREATED);
        }
    }


    /**
     * Save common settings
     *
     * @param array $licenceKey - cadviewer licence key
     *
     */
    public function SaveCommon() {

        $file = $_FILES['file'];

        // Check for errors
        if ($file['error'] > 0) {
            // Handle the error
            return new JSONResponse(array(), Http::STATUS_BAD_REQUEST);
        } else {

            // Construct path to converter folder
            $currentpath = __FILE__;
            $home_dir = explode("/cadviewer/", __FILE__)[0]."/cadviewer/converter";

            // include CADViewer config for be able to acces to the location of ax2024 executable file
            require($home_dir."/php/CADViewer_config.php");


            $output_detail = shell_exec($converterLocation.$ax2023_executable." -verify_detail");

            // extract information from key verification detail
            $lines = explode("\n", $output_detail);
            $number_of_users = -1;
            if (strpos($output_detail, "License Validated") !== false) {
                if (isset($lines[0]) && strpos($lines[0], "days until your") !== false) {
                    $number_of_users = intval($lines[3]);
                } else {
                    $number_of_users = intval($lines[2]);
                }
            }

            $licence_key_file = $licenseLocation."cvlicense.js";

            // Process the file
            $tmp_name = $file['tmp_name'];

            // Saving it to a directory
            $response =  move_uploaded_file($tmp_name, $licence_key_file);

            try {
                $content = file_get_contents($licence_key_file);
                if (preg_match('/"([^"]+)"/', $content, $m)) {
                    $this->config->SetLicenceKey($m[1]);
                }
            } catch (\Exception $e) {}
            $this->flushCache();

            return new JSONResponse(array("users" => $this->config->GetUsers($number_of_users), "licenceKey" => $this->config->GetLicenceKey(),), Http::STATUS_CREATED);
        }
    }

    /**
     * Run autoexchange licence key verification and return result
     *
     * @return array
     */
    public function checkAutoExchangeLicenceKey() {
        
		$this->checkIfLicenceIsPresent();
        // Construct path to converter folder
        $currentpath = __FILE__;
        $home_dir = explode("/cadviewer/", __FILE__)[0]."/cadviewer/converter";

        // include CADViewer config for be able to acces to the location of ax2024 executable file
        require($home_dir."/php/CADViewer_config.php");

        $script = $converterLocation.$ax2023_executable." -verify";
        
        // run the script and store output content for display it on the frontend 
        $output = shell_exec($script);
        $output_detail = shell_exec($converterLocation.$ax2023_executable." -verify_detail");
        
        $lines = explode("\n", $output_detail);
        $expiration_time = null;
        $version_number = "";
        $number_of_users = 0;
        $licensee = "";
        if (strpos($output, "License Validated") !== false) {
            if (isset($lines[0]) && strpos($lines[0], "days until your") !== false) {
                $pattern = "/\d+/";
                $matches = preg_match($pattern, $lines[0], $matchesArray);
                $expiration_time = intval($matchesArray[0]);

                $version_number = $lines[1];
                $number_of_users = intval($lines[3]);
                $licensee = trim($lines[4]);
            } else {
                $version_number = $lines[0];
                $number_of_users = intval($lines[2]);
                $licensee = trim($lines[3]);
            }
        } else {}


        $domaine_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" :"http") . "://$_SERVER[HTTP_HOST]";

        // get nextcloud instance id 
        $instance_id = \OC_Util::getInstanceId();

        return [
            "exectuable" => $ax2023_executable,
            "output" => $output,
            "output_detail" => $output_detail,
            "domaine_url" => $domaine_url,
            "instance_id" => $instance_id,
            "expiration_time" => $expiration_time,
            "version_number" => $version_number,
            "number_of_users" => $number_of_users,
            "users" => $this->config->GetUsers($number_of_users),
            "licensee" => $licensee
        ];
    }


	private function getWellConfiguredHtaccess() {
		$root_nextcloud_installation = getcwd();
		$htaccess_file = $root_nextcloud_installation . "/.htaccess";
		$can_write_in_htaccess_file = is_writable($htaccess_file);
		$htaccess_content = file_get_contents($htaccess_file);
		
		// verify if RewriteCond %{REQUEST_FILENAME} !/apps/cadviewer/converter/php/*\.* not present in .htaccess
		if (strpos($htaccess_content, "RewriteCond %{REQUEST_FILENAME} !/apps/cadviewer/converter/php/*\.*") == false) {
			// add RewriteCond %{REQUEST_FILENAME} !/apps/cadviewer/converter/php/*\.* in .htaccess
			$htaccess_content = str_replace("RewriteRule . index.php [PT,E=PATH_INFO:$1]", "RewriteCond %{REQUEST_FILENAME} !/apps/cadviewer/converter/php/*\.*\n  RewriteRule . index.php [PT,E=PATH_INFO:$1]", $htaccess_content);

		}

        return $htaccess_content;
	}

    public function index() {
        
		$this->checkIfLicenceIsPresent();
        $this->checkIfLicenceJsIsPresent();
		// Construct path to converter folder
        $currentpath = __FILE__;
        $home_dir = explode("/cadviewer/", __FILE__)[0]."/cadviewer";
        $info_file =  $home_dir."/appinfo/info.xml";

        // include CADViewer config for be able to acces to the location of ax2024 executable file
        $config_file = explode("/cadviewer/", __FILE__)[0]."/cadviewer/converter"."/php/CADViewer_config.php";
        require($config_file);

        $output_detail = shell_exec($converterLocation.$ax2023_executable." -verify_detail");

        $licence_key_file = $licenseLocation."cvlicense.js";
        $licence_axlic_file = $licenseLocation."axlic.key";

        // extract information from key verification detail
		$lines = explode("\n", $output_detail);
        $number_of_users = -1;
        if (strpos($output_detail, "License Validated") !== false) {
            if (isset($lines[0]) && strpos($lines[0], "days until your") !== false) {
                $number_of_users = intval($lines[3]);
            } else {
                $number_of_users = intval($lines[2]);
            }
        }

        // Read entire file into string
        $infoXmlfile = file_get_contents($info_file);
        
        // Convert xml string into an object and donvert into json
        $xmlEncodedData = json_encode(simplexml_load_string($infoXmlfile));
        
        // Convert into associative array
        $infoData = json_decode($xmlEncodedData, true);
        $name  = $infoData["name"];
        $version = $infoData["version"];

        $axFontMapFile = $home_dir."/converter/converters/ax2024/linux/ax_font_map.txt";

        $ax_font_map = "";
        try {
            $ax_font_map = file_get_contents($axFontMapFile);
        } catch (\Exception $e) {}

        $axFontUnMapFile = $home_dir."/converter/converters/ax2024/linux/ax_unmapped_fonts.txt";
        $ax_font_unmapped = "";
        try {
            $ax_font_unmapped = file_get_contents($axFontUnMapFile);
        } catch (\Exception $e) {}
        $parameters = json_decode($this->config->GetParameters(), true);
        $parameters[] = array(
            "parameter_conversion" => "",
            "folder_conversion"  => "*",
            "user_conversion" => "",
            "value_conversion" => "",
            "excluded_user_conversion" => "",
            "excluded_folder_conversion" => ""
        );
        $parameters[] = array(
            "parameter_conversion" => "",
            "folder_conversion"  => "*",
            "user_conversion" => "",
            "value_conversion" => "",
            "excluded_user_conversion" => "",
            "excluded_folder_conversion" => ""
        );
        $data = [
            "hash" => hash('sha256', file_get_contents($config_file)),
            "cached_conversion" => $cached_conversion,
            "name" => $name,
            "version" =>  $version,
            "ax_font_map" => $ax_font_map,
            "ax_font_unmapped" => $ax_font_unmapped,
            "licenceKey" => $this->config->GetLicenceKey(),
            "skin" => $this->config->GetSkin(),
            "parameters" => $parameters,
            "line_weight_factors" => json_decode($this->config->GetLineWeightFactors(), true),
            "autoexchange" => [
                "output" => "",
                "domaine_url" => "",
                "instance_id" => ""
            ],
            "show_users_list" => $number_of_users > 0,
            "number_of_users" => $number_of_users,
            "users" => $this->config->GetUsers($number_of_users),
            "haveLicence" => $this->checkIfUserDoesntHaveValidLicence(),
        ];
        return new TemplateResponse($this->appName, "settings", $data, "blank");
    }

    /**
     * Toggle cache conversion 
     * @param boolean $cached_conversion - true if cache conversion is activated elseway false
     */
    public function toggleCacheConversion($cached_conversion) {
        $config_file = explode("/cadviewer/", __FILE__)[0]."/cadviewer/converter/php/CADViewer_config.php";

        // replace $cached_conversion = true or $cached_conversion = false by $cached_conversion = $cached_conversion in config file
        $config_content = file_get_contents($config_file);
        $config_content = preg_replace("/cached_conversion = (true|false);/", "cached_conversion = ".var_export($cached_conversion, true).";", $config_content);
        file_put_contents($config_file, $config_content);
        return $cached_conversion;
    }


    
    /**
     * Save users list that have access to CADViewer
     */

    public function SaveUsers($users) {
        $this->config->SetUsers(json_encode($users));
        return array("users" => $users);
    }

    /**
     * Save converters parameters
     */
    public function SaveParameters(){

        $length  = intval($this->request->getParam("length",  "1"));
        $data = array();
        for ($i = 1; $i < $length+1; $i++) {
            $parameter_conversion = $this->request->getParam("parameter_conversion_".$i);
            $value_conversion = $this->request->getParam("value_conversion_".$i);
            $folder_conversion = $this->request->getParam("folder_conversion_".$i);
            $user_conversion = $this->request->getParam("user_conversion_".$i);
            $excluded_user_conversion = $this->request->getParam("excluded_user_conversion_".$i);
            $excluded_folder_conversion =  $this->request->getParam("excluded_folder_conversion_".$i);

            $data[] = array(
                "parameter_conversion"=> $parameter_conversion,
                "value_conversion" => $value_conversion,
                "folder_conversion" => $folder_conversion,
                "user_conversion" => $user_conversion,
                "excluded_user_conversion" => $excluded_user_conversion,
                "excluded_folder_conversion" => $excluded_folder_conversion
            );
        }

        $this->config->SetParameters(json_encode($data));

        return json_decode($this->config->GetParameters(), true);
    }

    /**
     * Save converters frontend parameters
     */
    public function SaveFrontendParameters() {

        $length  = intval($this->request->getParam("length",  "1"));
        $data = array();
        for ($i = 1; $i < $length+1; $i++) {
            $value_frontend = $this->request->getParam("value_frontend_".$i);
            $user_frontend = $this->request->getParam("user_frontend_".$i);
            $folder_frontend = $this->request->getParam("folder_frontend_".$i);
            $excluded_folder_frontend = $this->request->getParam("excluded_folder_frontend_".$i);
            $excluded_user_frontend = $this->request->getParam("excluded_user_frontend_".$i);

            $data[] = array(
                "value_frontend" => $value_frontend,
                "folder_frontend" => $folder_frontend,
                "user_frontend" => $user_frontend,
                "excluded_folder_frontend" => $excluded_folder_frontend,
                "excluded_user_frontend" => $excluded_user_frontend
            );
        }

        $this->config->SetLineWeightFactors(json_encode($data));

        return json_decode($this->config->GetLineWeightFactors(), true);
    }

    

    /**
     * Save common skin
     *
     * @param array $skin - cadviewer icon skin
     *
     * @return array
     */
    public function SaveSkin($skin) {

        $this->config->SetSkin($skin);
        
        return [
            "skin" => $this->config->GetSkin(),
        ];
    }


    public function doctor(){
		// Construct path to converter folder
        $currentpath = __FILE__;
        $home_dir = explode("/cadviewer/", __FILE__)[0]."/cadviewer/converter";

		// include CADViewer config for be able to acces to the location of ax2024 executable file
		require($home_dir."/php/CADViewer_config.php");

        $url = str_replace("converter/", "ajax/cadviewer/ping", $httpHost);
        // Check if w're are inside docker container
        if(is_file("/.dockerenv"))
            $url = preg_replace("/\:[0-9]{1,}/", "/", $url);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        $output = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $htaccess_is_whell_configured = $httpcode == 200;

        $can_write_in_log_file = is_writable($home_dir."/php/call-Api_Conversion_log.txt") && is_readable($home_dir."/php/call-Api_Conversion_log.txt");
        $can_execute_script_file = is_executable($converterLocation.$ax2023_executable);
        $exec_command_is_activate = function_exists('exec');
        $can_write_in_files_folder = is_writable($fileLocation) && is_readable($fileLocation);

        $can_write_in_files_folder_linux = is_writable($home_dir."converters/ax2024/linux/") 
                                    && is_readable($home_dir."converters/ax2024/linux/");

        $can_write_in_files_folder_merge = is_writable($home_dir."converters/files/merged/") 
                                    && is_readable($home_dir."converters/files/merged/"); 

        $can_write_in_files_folder_print = is_writable($home_dir."converters/files/print/") 
                                    && is_readable($home_dir."converters/files/print/");

        $can_write_in_files_folder_pdf = is_writable($home_dir."converters/files/pdf/") 
                                    && is_readable($home_dir."converters/files/pdf/");

        $can_write_in_files_folder_redlines = is_writable($home_dir."content/redlines/") 
                                    && is_readable($home_dir."content/redlines/");

        $can_write_in_files_folder_redlines_v7 = is_writable($home_dir."content/redlines/v7/") 
                                    && is_readable($home_dir."content/redlines/v7/");

        $can_write_in_files_folder_php = is_writable($home_dir . "php/")
                                    && is_readable($home_dir . "php/");

		return new JSONResponse(array(
            "can_write_in_log_file"  => $can_write_in_log_file,
            "can_write_in_files_folder"  => $can_write_in_files_folder,
            "can_execute_script_file"  => $can_execute_script_file,
            "exec_command_is_activate"  => $exec_command_is_activate,
            "htaccess_is_whell_configured"  => $htaccess_is_whell_configured,
            "can_write_in_files_folder_linux" => $can_write_in_files_folder_linux,
            "can_write_in_files_folder_merge" => $can_write_in_files_folder_merge ,
            "can_write_in_files_folder_print" => $can_write_in_files_folder_print,
            "can_write_in_files_folder_pdf" => $can_write_in_files_folder_pdf,
            "can_write_in_files_folder_redlines" => $can_write_in_files_folder_redlines,
            "can_write_in_files_folder_redlines_v7" => $can_write_in_files_folder_redlines_v7,
            "can_write_in_files_folder_php" => $can_write_in_files_folder_php,
            "htaccess_content" => $this->getWellConfiguredHtaccess(),
        ), Http::STATUS_OK);
	}

    public function  displayLog() {
        // Construct path to converter folder
        $currentpath = __FILE__;
        $home_dir = explode("/cadviewer/", __FILE__)[0]."/cadviewer/converter";

		// include CADViewer config for be able to acces to the location of ax2023 executable file
		require($home_dir."/php/CADViewer_config.php");

        $url = str_replace("converter/", "ajax/cadviewer/ping", $httpHost);
        // Check if w're are inside docker container
        if(is_file("/.dockerenv"))
            $url = str_replace(":8080/", "/", $url);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        $output = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $htaccess_is_whell_configured = $httpcode == 200;

        $logFile = $home_dir."/php/call-Api_Conversion_log.txt";
        $log_content = "";
        try {
            $log_content = file_get_contents($logFile);
        } catch (\Exception $e) {
		}

        return new JSONResponse(array(
            "log_content"  => $log_content,
        ), Http::STATUS_OK);
    }


    /** 
     * Save ax font map content in file 
    */
    public function saveAxFontMap($ax_font_map) {
        
        
		// Construct path to converter folder
        $currentpath = __FILE__;
        $home_dir = explode("/cadviewer/", __FILE__)[0]."/cadviewer/converter";

        $axFontMapFile = $home_dir."/converters/ax2024/linux/ax_font_map.txt";

        file_put_contents($axFontMapFile, $ax_font_map);
        
        return new JSONResponse(array(), Http::STATUS_OK);
    }


	public function flushCache(){
		// Construct path to converter folder
        $currentpath = __FILE__;
        $home_dir = explode("/cadviewer/", __FILE__)[0]."/cadviewer/converter";

		// include CADViewer config for be able to acces to the location of ax2024 executable file
		require($home_dir."/php/CADViewer_config.php");

		
		$files = glob($fileLocation."*"); //get all file names
		foreach($files as $file){
			if(is_file($file))
			unlink($file); //delete file
		}

		return new JSONResponse(array(), Http::STATUS_NO_CONTENT);
	}

	public function demoLicence($email, $company_name, $url) {

        // Construct path to converter folder
        $currentpath = __FILE__;
        $home_dir = explode("/cadviewer/", __FILE__)[0]."/cadviewer/converter";

		// include CADViewer config for be able to acces to the location of ax2024 executable file
		require($home_dir."/php/CADViewer_config.php");

	   /*
	    Get Mac address with command:
	    */

	   $mac_ip = shell_exec("cat /sys/class/net/eth0/address");
	   // remove \n
	   $mac_ip = str_replace("\n", "", $mac_ip);

        $body = array(
            "mac_ip" => $mac_ip,
            "url" => $url,
            "email" => $email,
            "company_name" => $company_name
        );

        $url = "https://store.cadviewer.com/wp-json/custom/v2/cadviewers/demo";

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => json_encode($body),
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
          ),
        ));

        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $output = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($output, true);

        if (!isset($response["cvlicense"]) || !isset($response["axlic"])){
            return new JSONResponse(
                array("message" => $response["message"], "status" => "failed", "httpcode" => $httpcode),
                Http::STATUS_OK
            );
        }

        // save cvlicense
        $cvlicense_file = $licenseLocation."cvlicense.js";
        $cvlicense_content = $response["cvlicense"];
        file_put_contents($cvlicense_file, $cvlicense_content);
        $this->config->SetLicenceKey($cvlicense_content);

        $axlic_file = $licenseLocation."axlic.key";
        $axlic_content = $response["axlic"];
        file_put_contents($axlic_file, $axlic_content);
        $this->config->SetAxlicLicenceKey($axlic_content);
        $this->flushCache();

        // save axlic licence
        return new JSONResponse(
            array("message" => $response["message"], "status" => "success", "httpcode" => $httpcode),
            Http::STATUS_OK
        );
	}

	public function checkIfUserDoesntHaveValidLicence() {

        // Construct path to converter folder
        $currentpath = __FILE__;
        $home_dir = explode("/cadviewer/", __FILE__)[0]."/cadviewer/converter";

		// include CADViewer config for be able to acces to the location of ax2024 executable file
		require($home_dir."/php/CADViewer_config.php");

		$output_detail = shell_exec($converterLocation.$ax2023_executable." -verify_detail");

		// if expired, return success for display demo mode
        if (strpos($output_detail, 'Expired') !== false){
            $this->flushCache();
            return "demo";
        }

        // check if is trial version
        if (strpos($output_detail, 'TRIAL') !== false){
            return "trial_version";
        }

        // check if is trial version
        if (strpos($output_detail, 'DEMO') !== false){
            return "trial_version";
        }

        // if there is no licence file, return success for display demo mode
        if (strpos($output_detail, 'Unable to Read/Open License') !== false){
            return "demo";
        }

        if (strpos($output_detail, "License Validated") !== false) {
            return "validated";
        }
        return "demo";
	}
}
