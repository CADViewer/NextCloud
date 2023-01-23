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
            $pos1 = stripos($currentpath, "cadviewer");
            $home_dir = substr($currentpath, 0, $pos1+ 10)."converter";

            // include CADViewer config for be able to acces to the location of ax2023 executable file
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
        $pos1 = stripos($currentpath, "cadviewer");
        $home_dir = substr($currentpath, 0, $pos1+ 10)."converter";

        // include CADViewer config for be able to acces to the location of ax2023 executable file
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

    public function index() {
    

        $data = [
            "licenceKey" => $this->config->GetLicenceKey(),
            "autoexchange" => $this->checkAutoExchangeLicenceKey()
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

}
