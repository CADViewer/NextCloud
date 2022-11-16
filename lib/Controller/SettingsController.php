<?php
namespace OCA\Cadviewer\Controller;

use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\TemplateResponse;
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
                                    AppConfig $config,
                                    ) {
        parent::__construct($AppName, $request);

        $this->urlGenerator = $urlGenerator;
        $this->trans = $trans;
        $this->logger = $logger;
        $this->config = $config;
    }


    public function index() {
        $data = [
            "licenceKey" => $this->config->GetLicenceKey(),
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
