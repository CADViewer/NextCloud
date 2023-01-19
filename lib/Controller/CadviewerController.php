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
	}


	public function getFile($directory, $fileName){
		\OC_Util::tearDownFS();
		\OC_Util::setupFS($this->userId);
		return Filesystem::getView()->getLocalFile($directory . '/' . $fileName);
	}


	/**
	 * Create a folder "CADViewer - Markup" at the root directory of nextcloud data and share with all user 
	 */

	private function createCadviewerFolder(){

		try {

			// check if nextcloud user is admin
			if ($this->groupManager->isAdmin($this->userId)){
				
				\OC_Util::tearDownFS();
				\OC_Util::setupFS($this->userId);

				// check if folder already exists
				if (Filesystem::getView()->file_exists($this->markup_folder_name)){
					// return;
				}

				// Create a folder at the root directory of nextcloud data "CADViewer - Markup"
				$res  = Filesystem::getView()->mkdir($this->markup_folder_name);
				
				// Share with all user
				
				// Retrieve the user's folder
				$userFolder = $this->rootFolder->getUserFolder($this->userId);

				// Try to get the folder with the specified name
				try {
					$folder = $userFolder->get($this->markup_folder_name);
					if ($folder) {
						// get list of users id present in system
						$userManager = \OC::$server->getUserManager();
						foreach ($userManager->getBackends() as $backend) {
							$users = $backend->getUsers();

							foreach ($users as $userId) {
								if ($userId != $this->userId) {
									// Set the share settings for the folder
									$share = $this->shareManager->newShare();
									$share->setNode($folder)
										->setShareType(IShare::TYPE_USER)
										->setPermissions(\OCP\Constants::PERMISSION_ALL)
										->setSharedBy($this->userId)
										->setSharedWith($userId)
										->setStatus(IShare::STATUS_ACCEPTED);
									$this->shareManager->createShare($share);
								}
							}
						}
					}
				} catch (\OCP\Files\NotFoundException $e) {
					// Handle the exception if the folder was not found
				}

			}
		} catch (\Exception $e) {
		}

	}

	
	/**
	 *  @NoAdminRequired
	 */
	public function movePdf($pdfFileName){

		// Construct path to converter folder
        $currentpath = __FILE__;
        $pos1 = stripos($currentpath, "cadviewer");
        $home_dir = substr($currentpath, 0, $pos1+ 10)."converter";

        // include CADViewer config for be able to acces to the location of ax2023 executable file
        require($home_dir."/php/CADViewer_config.php");

		$pdfRelativePath = $fileLocation . "" . $pdfFileName;
		
		// move pdf into markup_folder
		$markup_folder = $this->markup_folder_name;

		$file = $this->getFile($markup_folder."/".$pdfFileName, "");

		if (!rename($pdfRelativePath, $file)) {
            return new JSONResponse(array(), Http::STATUS_EXPECTATION_FAILED);
		}
		// Notify nextcloud the presence of new file
		Filesystem::touch($markup_folder."/".$pdfFileName, "");
		return new JSONResponse(array(), Http::STATUS_NO_CONTENT);
	}

	/**
	 *  @NoAdminRequired
	 */
	public function path($nameOfFile, $directory){

		$this->createCadviewerFolder();


		if ($this->encryptionManager->isEnabled()) {
			$response = array();
			$response = array_merge($response, array("code" => 0, "desc" => $this->l->t("Encryption is not supported yet")));
			return json_encode($response);
		}
		
		$file = $this->getFile($directory."/".$nameOfFile, "");

		$dir = dirname($file);
		
		$response = array();
		$response["licenceKey"] = $this->appConfig->GetLicenceKey();
		$response["path"] = $dir;
		$response["serverLocation"] = str_replace("lib/Controller", "converter", dirname(__FILE__));
		
		return $response;
	}
	
	
}
