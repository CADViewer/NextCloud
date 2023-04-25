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

            $axlic_file = $licenseLocation."axlic.key";

            // Process the file
            $tmp_name = $file['tmp_name'];

            // Saving it to a directory
            move_uploaded_file($tmp_name, $axlic_file);
            
            return new JSONResponse(array(), Http::STATUS_CREATED);
        }
    }

    /**
     * Run autoexchange licence key verification and return result
     *
     * @return array
     */
    public function checkAutoExchangeLicenceKey() {
        
        // Construct path to converter folder
        $currentpath = __FILE__;
        $home_dir = explode("/cadviewer/", __FILE__)[0]."/cadviewer/converter";

        // include CADViewer config for be able to acces to the location of ax2024 executable file
        require($home_dir."/php/CADViewer_config.php");

        $script = $converterLocation.$ax2023_executable." -verify";
        
        // run the script and store output content for display it on the frontend 
        $output = shell_exec($script);

        $domaine_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";

        // get nextcloud instance id 
        $instance_id = \OC_Util::getInstanceId();

        return [
            "output" => $output,
            "domaine_url" => $domaine_url,
            "instance_id" => $instance_id
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
    
		// Construct path to converter folder
        $currentpath = __FILE__;
        $home_dir = explode("/cadviewer/", __FILE__)[0]."/cadviewer";
        $info_file =  $home_dir."/appinfo/info.xml";

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

        $data = [
            "name" => $name,
            "version" =>  $version,
            "ax_font_map" => $ax_font_map,
            "ax_font_unmapped" => $ax_font_unmapped,
            "licenceKey" => $this->config->GetLicenceKey(),
            "skin" => $this->config->GetSkin(),
            "autoexchange" => [
                "output" => "",
                "domaine_url" => "",
                "instance_id" => ""
            ]
        ];
        return new TemplateResponse($this->appName, "settings", $data, "blank");
    }


    /**
     * Save common settings
     *
     * @param array $licenceKey - cadviewer licence key
     *
     * @return array
     */
    public function SaveCommon($licenceKey) {

        $this->config->SetLicenceKey($licenceKey);
        
        return [
            "licenceKey" => $this->config->GetLicenceKey(),
        ];
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

}
