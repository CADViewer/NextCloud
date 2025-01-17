<?php

namespace OCA\Cadviewer\Controller;

use OCP\IRequest;
use OCP\AppFramework\Controller;
use OCP\IL10N;
use OC\Files\Filesystem;
use OCP\Encryption\IManager;
use OCP\Share\IManager as IShareManager;
use OC\Group\Manager as GroupManager;
use OC\User\Session;
use OCP\IUserSession;
use OCP\IGroupManager;
use OCP\Share\IShare;
use OCP\Files\IRootFolder;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\JSONResponse;

use OCA\Cadviewer\AppConfig;
use OCA\Cadviewer\Controller\SettingsController;

class CadviewerController extends Controller {

	/** @var IL10N */
	private $l;

	/** @var IManager */

    /**
     * Application configuration
     *
     * @var AppConfig
     */
    public $appConfig;

	private $encryptionManager;
	private Session $userSession;
	private IShareManager $shareManager;
	private GroupManager $groupManager;
	private IRootFolder $rootFolder;
	private SettingsController $settingsController;
	private $userId;

	private $markup_folder_name = "CADViewer - Markup";

	public function __construct(
		$AppName, 
		IRequest $request, 
		IL10N $l, 
		IGroupManager $groupManager,
		IUserSession $userSession,
		IManager $encryptionManager, 
		IShareManager $shareManager,
		IRootFolder $rootFolder,
		SettingsController $settingsController,
		$UserId
	){
		parent::__construct($AppName, $request);
		$this->l = $l;
        $this->appConfig = new AppConfig($AppName);
		$this->userSession = $userSession;
		$this->groupManager = $groupManager;
		$this->encryptionManager = $encryptionManager;
		$this->shareManager = $shareManager;
		$this->rootFolder = $rootFolder;
		$this->userId = $UserId;
		$this->settingsController = $settingsController;
	}


	public function getFile($directory, $fileName){
		// Construct path to converter folder
        $currentpath = __FILE__;
        $home_dir = explode("/cadviewer/", __FILE__)[0]."/cadviewer/converter";

        // include CADViewer config for be able to acces to the location of ax2023 executable file
        require($home_dir."/php/CADViewer_config.php");

		
		\OC_Util::tearDownFS();
		\OC_Util::setupFS($this->userId);
		
		$view = Filesystem::getView();
		$path = $view->getLocalFile($directory . '/' . $fileName);
		// check if /tmp present in $path to persist it
		if (strpos($path, '/tmp') !== false) {
			$newPath = $fileLocation . "" .$fileName;
			// get the last modification time of the file
			$mtime = $view->filemtime($directory . '/' . $fileName);
			rename($path,  $newPath);
			$path = $newPath;
			// set the last modification time of the file
			touch($path, $mtime, $mtime);
		} 
		return $path;
	}


	/**
	 * Create a folder "CADViewer - Markup" at the root directory of nextcloud data and share with all user 
	 */

	private function createCadviewerFolder(){

		try {

			\OC_Util::tearDownFS();
			\OC_Util::setupFS($this->userId);

			// check if folder already exists
			if (Filesystem::getView()->file_exists($this->markup_folder_name)){
				return;
			}

			// Create a folder at the root directory of nextcloud data "CADViewer - Markup"
			$res  = Filesystem::getView()->mkdir($this->markup_folder_name);
			
		} catch (\Exception $e) {}

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

	/**
	 *  @NoAdminRequired
	 */

	public function compareWithOwnVersion(){

		// Construct path to converter folder
        $home_dir = explode("/cadviewer/", __FILE__)[0]."/cadviewer/converter";

		// include CADViewer config for be able to acces to the location of ax2024 executable file
		require($home_dir."/php/CADViewer_config.php");
		// create compare folder if not exists
		if (!file_exists($fileLocation."compare")) {
			mkdir($fileLocation."compare", 0777, true);
		}
		$file_path = $fileLocation."compare/".$_POST['filename'];
		$base64_string = $_POST['file'];
		$data = explode( ',', $base64_string );
		$ifp = fopen( $file_path, 'wb' ); 
		fwrite( $ifp, base64_decode( $data[ 1 ] ) );
		// clean up the file resource
		fclose( $ifp ); 

		// return object  with path to file
		return new JSONResponse(array("path" => $file_path), Http::STATUS_OK);
	}
	
	/**
	 *  @NoAdminRequired
	 */
	public function movePdf($pdfFileName, $pdfFolderName){

		$this->createCadviewerFolder();

		// Construct path to converter folder
        $currentpath = __FILE__;
        $home_dir = explode("/cadviewer/", __FILE__)[0]."/cadviewer/converter";

        // include CADViewer config for be able to acces to the location of ax2023 executable file
        require($home_dir."/php/CADViewer_config.php");

		$pdfRelativePath = $fileLocation . "" . $pdfFileName;
		
		// move pdf into markup_folder
		$markup_folder = $this->markup_folder_name;
		if (strpos($pdfFolderName, '/') === 0) {
			$pdfFolderName  = substr($pdfFolderName, 1);
		}
		
		if ($pdfFolderName != null &&  $pdfFolderName != "markup") {
			$markup_folder = $pdfFolderName;
		}

		$file = $this->getFile($markup_folder."/".$pdfFileName, "");

		if (!rename($pdfRelativePath, $file)) {
            return new JSONResponse(array(), Http::STATUS_EXPECTATION_FAILED);
		}

		$userFolder = $this->rootFolder->getUserFolder($this->userId);
		$markupFolder = $userFolder->get($markup_folder);
		$savedFile = $markupFolder->newFile($pdfFileName);
		$savedFile->touch();

		return new JSONResponse(array(), Http::STATUS_NO_CONTENT);
	}

	private function checkIfNumberOfUsersLimitation()  {

		// Construct path to converter folder
        $currentpath = __FILE__;
        $home_dir = explode("/cadviewer/", __FILE__)[0]."/cadviewer/converter";

        // include CADViewer config for be able to acces to the location of ax2024 executable file
        require($home_dir."/php/CADViewer_config.php");

        $output_detail = shell_exec($converterLocation.$ax2023_executable." -verify_detail");
        
		// if expired, return success for display demo mode
		if (strpos($output_detail, 'Expired') !== false){
			$this->flushCache();
			return "success";
		}
		// if there is no licence file, return success for display demo mode
		if (strpos($output_detail, 'Unable to Read/Open License') !== false){
			return "success";
		}

		// extract information from key verification detail
		$lines = explode("\n", $output_detail);
        $expiration_time = "";
        $version_number = "";
        $number_of_users = -1;
        $licensee = "";
        if (strpos($output_detail, "License Validated") !== false) {
            if (isset($lines[0]) && strpos($lines[0], "days until your") !== false) {
                $expiration_time = $lines[0];
                $version_number = $lines[1];
                $number_of_users = intval($lines[3]);
                $licensee = trim($lines[4]);
            } else {
                $version_number = $lines[0];
                $number_of_users = intval($lines[2]);
                $licensee = trim($lines[3]);
            }
        } else {
			return "success";
		}
		$maximun_number_of_user = $number_of_users;

		if ($maximun_number_of_user == 0) {
			return "success";
		}
		
		$users = $this->appConfig->GetUsers($maximun_number_of_user);

		$groupManager = \OC::$server->getGroupManager();
		$found = false;

		// check if $this->userId in $users
		if (in_array($this->userId, $users)) {
			$found = true;
		}

		if (!$found) {
			$response = array();
			$response = array_merge($response, array("code" => 0, "desc" => $this->l->t("Before access to this feature administrator need to grant you access")));
			return $response;
		}
		return "success";
	}

	/**
	 *  @NoAdminRequired
	 */
	public function path($nameOfFile, $directory){

        $this->settingsController->checkIfLicenceIsPresent();
        $this->settingsController->checkIfLicenceJsIsPresent();

		// check if call-Api_Conversion_log.txt not exec return1  0  then flush cache
		
		$home_dir = explode("/cadviewer/", __FILE__)[0]."/cadviewer/converter/php";

		$log_file = $home_dir."/call-Api_Conversion_log.txt";
		$contents = file_get_contents($log_file);
		if (str_contains($contents, 'exec return1  0') == false && str_contains($contents, 'exec return1 0')  == false) {
			$this->flushCache();
		}

		// check if there new version installed and if yes flush cache
		$appinfo_dir = explode("/cadviewer/", __FILE__)[0]."/cadviewer/appinfo";
		$version_file = $appinfo_dir."/version.txt";
		$last_installed_version = file_get_contents($version_file);

		/// read version in info.xml
		$version_info_xml = $appinfo_dir."/info.xml";
		// Regular expression pattern to extract version number
		$pattern = '/<version>(.*?)<\/version>/s'; // The 's' modifier allows dot '.' to match newline characters

		// Perform the regular expression match
		preg_match($pattern, file_get_contents($version_info_xml), $matches);
		$version = $matches[1];
		if ($version != $last_installed_version) {
			$this->flushCache();
			file_put_contents($version_file, $version);
		}

		$res = $this->checkIfNumberOfUsersLimitation();
		if ($res != "success") {
			return $res;
		}
		$res = $this->checkIfHtaccessIsWellConfigured();
		if ($res != "success") {
			return $res;
		}
		$res = $this->checkIfWereAreOnARMArchitecture();
		if ($res != "success") {
			return $res;
		}



		if ($this->encryptionManager->isEnabled()) {
			$response = array();
			$response = array_merge($response, array("code" => 0, "desc" => $this->l->t("Encryption is not supported yet")));
			return $response;
		}
		
		$file = $this->getFile($directory, $nameOfFile);
		
		$dir = dirname($file);
		
		$userFolder = $this->rootFolder->getUserFolder($this->userId);
		$fileStat = $userFolder->get($directory."/".$nameOfFile)->stat();
		$response = array();
		$response["licenceKey"] = $this->appConfig->GetLicenceKey();
		$response["skin"] = $this->appConfig->GetSkin();
		$lineWeightFactors =  json_decode($this->appConfig->GetLineWeightFactors(), true);

        $zoom_image_wallpaper_parameters = json_decode($this->appConfig->GetZoomImageWallpaperParameters(), true);
        $scroll_wheel_parameters = json_decode($this->appConfig->GetScrollWheelParameters(), true);
        $response["zoom_image_wallpaper_parameters"] = $zoom_image_wallpaper_parameters;
        $response["scroll_wheel_parameters"] = $scroll_wheel_parameters;

		$response["lineWeightFactor"] = null;
		foreach ($lineWeightFactors as $key => $value) {
			if(
				($value["folder_frontend"] === $directory || $value["folder_frontend"] === "*") && 
				($value["user_frontend"] === "/".$this->userId || $value["user_frontend"] === "*")
			) {
				$found_user =  false;
				$found_folder =  false;
				foreach(explode(",", $value["excluded_user_frontend"] ?:  "") as $value_user) {
					if ($value_user === "/".$this->userId) {
						$found_user = true;
						break;
					}
				}
				foreach(explode(",", $value["excluded_folder_frontend"] ?:  "") as $value_folder) {
					if ($value_folder === $directory) {
						$found_folder = true;
						break;
					}
				}
				if ($found_user and $found_folder)
					continue;
				$response["lineWeightFactor"] = intval($value["value_frontend"]);			
			}
		}
		$parameters = json_decode($this->appConfig->GetParameters(), true);
		$response["parameters"] = array();
		$i = 1;
		foreach ($parameters as $key => $value) {
			if(
				($value["folder_conversion"] === $directory || $value["folder_conversion"] === "*") && 
				($value["user_conversion"] === "/".$this->userId || $value["user_conversion"] === "*")
			) {
				$found_user =  false;
				$found_folder =  false;
				foreach(explode(",", $value["excluded_user_conversion"] ?:  "") as $value_user) {
					if ($value_user === "/".$this->userId) {
						$found_user = true;
						break;
					}
				}
				foreach(explode(",", $value["excluded_folder_conversion"] ?:  "") as $value_folder) {
					if ($value_folder === $directory) {
						$found_folder = true;
						break;
					}
				}
				if ($found_user and $found_folder)
					continue;
				$response["parameters"]["parameter_".$i] = $value["parameter_conversion"];
				$response["parameters"]["value_".$i] = $value["value_conversion"];
				$i += 1;
			}
		}



		$folder = "apps";

		// check extra-apps in $_SERVER['REQUEST_URI']
		if (strpos($_SERVER['REQUEST_URI'], "extra-apps") !== false) {
			$folder = "extra-apps";
		}

		// check custom_apps in $_SERVER['REQUEST_URI']
		if (strpos($_SERVER['REQUEST_URI'], "custom_apps") !== false) {
			$folder = "custom_apps";
		}

		$response["path"] = $dir;
		$response["size"] = $fileStat["size"];
		$response["ISOtimeStamp"] = date(DATE_ISO8601, $fileStat["mtime"]);
		$response["serverLocation"] = str_replace("lib/Controller", "converter", dirname(__FILE__));
		$response["serverUrl"] = "/".$folder."/cadviewer/";
// 		check if request url have /index.php/apps/ inside it, if is the case then add it on serverUrl
        if (strpos($_SERVER['REQUEST_URI'], "/index.php/apps/") !== false) {
            $response["serverUrl"] = "/index.php".$response["serverUrl"];
        }
        $response["test"] = $_SERVER['REQUEST_URI'];
		$response["haveLicence"] = $this->settingsController->checkIfUserDoesntHaveValidLicence();
		
		return $response;
	}

	private function checkIfWereAreOnARMArchitecture() {
		$architecture = php_uname('m');
		if ($architecture == "armv7l" || $architecture === 'arm64' || $architecture === 'aarch64' ) {
			$response = array();
			$response = array_merge($response, array(
			    "architecture" => $architecture,
			    "code" => 0,
			    "desc" => $this->l->t("ARM architecture is not supported yet"),
			));
			return $response;
		}
		return "success";
	}
	
	private function checkIfHtaccessIsWellConfigured()  {
		
		
		$root_path_server = $_SERVER['DOCUMENT_ROOT'];
		$htaccess_path = $root_path_server . "/.htaccess";
		// check if file not exist return "success"
		if (!file_exists($htaccess_path)) {
			return "success";
		}
		$htaccess_content = file_get_contents($htaccess_path);

        // if htaccess not content #### DO NOT CHANGE ANYTHING ABOVE THIS LINE #### then skip
        if (strpos($htaccess_content, "#### DO NOT CHANGE ANYTHING ABOVE THIS LINE ####") === false) {
            return "success";
        }

		// identify if cadviewer is install inside apps or extra-apps folder 

		$folder = "apps";

		// check extra-apps in $_SERVER['REQUEST_URI']
		if (strpos($_SERVER['REQUEST_URI'], "extra-apps") !== false) {
			$folder = "extra-apps";
		}

		// check custom_apps in $_SERVER['REQUEST_URI']
		if (strpos($_SERVER['REQUEST_URI'], "custom_apps") !== false) {
			$folder = "custom_apps";
		}

		// try to add the htaccess_code in .htaccess if is not possible then returne error message with instruction to user
		$htaccess_code = "  RewriteCond %{REQUEST_FILENAME} !/".$folder."/cadviewer/converter/php/*\.* \n";
		
		// check if the htaccess_code is already present in .htaccess
		if (strpos($htaccess_content, $htaccess_code) !== false) {
			return "success";
		}

		$searchLine = "  RewriteRule . index.php [PT,E=PATH_INFO:$1]";

		$htaccess_content = str_replace($searchLine, $htaccess_code."".$searchLine, $htaccess_content);
		
		// save the new content of .htaccess
		$res = file_put_contents($htaccess_path, $htaccess_content);
		$msg = "
			You need to configure your .htaccess file to add \n
			\"".$htaccess_code."\"
			before the line 
			\"".$searchLine."\"
		";


		if ($res === false) {
			$response = array();
			$response = array_merge($response, array(
				"code" => 0, 
				"desc" => $msg
			));
			return $response;
		}

		return "success";
	}


	/**
	 * @NoAdminRequired
     * @NoCSRFRequired
	 * @PublicPage
	 */
	public function ping(){
		return new JSONResponse(array(), Http::STATUS_OK);
	}
	
}
