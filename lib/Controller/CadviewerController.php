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
		$cadviewer_group_name = "CADViewer";
		$maximun_number_of_user = 10;

		$groupManager = \OC::$server->getGroupManager();
		$found = false;

		foreach ($groupManager->getBackends() as $backend) {
			$groups =  $backend->getGroups();
			foreach ($groups as $group) {
				if ($group == $cadviewer_group_name){
					$users = $backend->usersInGroup($group);
					if (count($users) > $maximun_number_of_user) {
						$response = array();
						$response = array_merge($response, array("code" => 0, "desc" => $this->l->t("The number of users is limited to")." ".$maximun_number_of_user));
						return $response;
					}
					// check if current user is in the group
					$found_user = false;
					
					foreach ($users as $user) {
						if ($user == $this->userId) {
							$found = true;
							$found_user = true;
							break;
						}
					}
					if (!$found_user) {
						$response = array();
						$response = array_merge($response, array("code" => 0, "desc" => $this->l->t("Before access to this feature Administrator need to add yourself to the group CADViewer")));
						return $response;
					}
				}
			}
		}

		if (!$found) {
			$response = array();
			$response = array_merge($response, array("code" => 0, "desc" => $this->l->t("Before access to this feature you need to create a group called CADViewer and add users to it")));
			return $response;
		}
		return "success";
	}

	/**
	 *  @NoAdminRequired
	 */
	public function path($nameOfFile, $directory){

		$res = $this->checkIfNumberOfUsersLimitation();
		$this->settingsController->checkIfLicenceIsPresent();
		if ($res != "success") {
			// return $res; // ! todo uncomment this line
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
		$response["parameters"] = json_decode($this->appConfig->GetParameters(), true);
		$response["skin"] = $this->appConfig->GetSkin();
		$response["lineWeightFactor"] = $this->appConfig->GetLineWeightFactor();
		$response["path"] = $dir;
		$response["size"] = $fileStat["size"];
		$response["ISOtimeStamp"] = date(DATE_ISO8601, $fileStat["mtime"]);
		$response["serverLocation"] = str_replace("lib/Controller", "converter", dirname(__FILE__));
		
		return $response;
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
