<?php

namespace OCA\Cadviewer\Controller;

use OCP\IRequest;
use OCP\AppFramework\Controller;
use OCP\IL10N;
use OC\Files\Filesystem;
use OCP\Encryption\IManager;

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

	protected $encryptionManager;

	private $userId;

	public function __construct($AppName, IRequest $request, IL10N $l, IManager $encryptionManager, $UserId){
		parent::__construct($AppName, $request);
		$this->l = $l;
		$this->encryptionManager = $encryptionManager;
		$this->userId = $UserId;
        $this->appConfig = new AppConfig($AppName);
	}


	public function getFile($directory, $fileName){
		\OC_Util::tearDownFS();
		\OC_Util::setupFS($this->userId);
		return Filesystem::getView()->getLocalFile($directory . '/' . $fileName);
	}


	/**
	 *  @NoAdminRequired
	 */
	public function path($nameOfFile, $directory){

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
		
		return $response;
	}
	
	
}
