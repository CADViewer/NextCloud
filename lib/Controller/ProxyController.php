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
use OCP\AppFramework\Http\Response;
use OCP\AppFramework\Http\DownloadResponse;

use OCA\Cadviewer\AppConfig;

class ProxyController extends Controller {

	public function __construct(
		$AppName,
		IRequest $request,
    ){
		parent::__construct($AppName, $request);
	}

	/**
	 * @NoAdminRequired
     * @NoCSRFRequired
    */
	public function apiConversion(){
        $currentpath = __FILE__;
        $home_dir = explode("/cadviewer/", __FILE__)[0]."/cadviewer/converter";
        $response = require($home_dir."/php/call-Api_Conversion.php");
		return $response;
	}

	/**
	 * @NoAdminRequired
     * @NoCSRFRequired
    */
    public function getFile(){
        $currentpath = __FILE__;
        $home_dir = explode("/cadviewer/", __FILE__)[0]."/cadviewer/converter";
        $response = require($home_dir."/php/getFile_09.php");
        return $response;
    }

	/**
	 * @NoAdminRequired
     * @NoCSRFRequired
    */
    public function info(){
        $currentpath = __FILE__;
        $home_dir = explode("/cadviewer/", __FILE__)[0]."/cadviewer/converter";
        $response = require($home_dir."/php/info.php");
        return $response;
    }

	/**
	 * @NoAdminRequired
     * @NoCSRFRequired
    */
    public function loadFile(){
        $currentpath = __FILE__;
        $home_dir = explode("/cadviewer/", __FILE__)[0]."/cadviewer/converter";
        $response = require($home_dir."/php/load-file.php");
        return $response;
    }

	/**
	 * @NoAdminRequired
     * @NoCSRFRequired
    */
    public function makeSinglePagePdf(){
        $currentpath = __FILE__;
        $home_dir = explode("/cadviewer/", __FILE__)[0]."/cadviewer/converter";
        $response = require($home_dir."/php/make_singlepage_pdf.php");
        return $response;
    }

	/**
	 * @NoAdminRequired
     * @NoCSRFRequired
    */
    public function saveFile(){
        $currentpath = __FILE__;
        $home_dir = explode("/cadviewer/", __FILE__)[0]."/cadviewer/converter";
        $response = require($home_dir."/php/save-file.php");
        return $response;
    }

	/**
	 * @NoAdminRequired
     * @NoCSRFRequired
    */
    public function testRun(){
        $currentpath = __FILE__;
        $home_dir = explode("/cadviewer/", __FILE__)[0]."/cadviewer/converter";
        $response = require($home_dir."/php/testrun.php");
        return $response;
    }

	/**
	 * @NoAdminRequired
     * @NoCSRFRequired
    */
     public function assets(string $path) {
        // return content of file inside assets folder
        $currentpath = __FILE__;
        $home_dir = explode("/cadviewer/", __FILE__)[0]."/cadviewer/assets";

        $file = $home_dir."/".$path;
        $file = str_replace("//", "/", $file);

        if (file_exists($file)) {
            $mine = mime_content_type($file);
            if (strpos($file, 'css') !== false) {
                $mine = 'text/css;charset=UTF-8';
            }
            header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
            header("Cache-Control: public"); // needed for internet explorer
            header("Content-Type: ".$mine);
            header("Content-Transfer-Encoding: Binary");
            header("Content-Length:".filesize($file));
            readfile($file);
            die();
        } else {
            return new Response('File not found', Http::STATUS_NOT_FOUND);
        }
    }

}
