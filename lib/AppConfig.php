<?php

namespace OCA\Cadviewer;


use OCP\IConfig;
use OCP\ILogger;

/**
 * Application configutarion
 *
 * @package OCA\Cadviewer
 */
class AppConfig {

    /**
     * Application name
     *
     * @var string
     */
    private $appName;

    /**
     * Config service
     *
     * @var IConfig
     */
    private $config;

    /**
     * Logger
     *
     * @var ILogger
     */
    private $logger;


    /**
     * The licence key for cadviewer
     *
     * @var string
     */
    private $_licencekey = "LicenceKey";

    /**
     * The skin for cadviewer icons
     * 
     * @var string
     */
    private $_skin = "Skin";

    /** 
     * @param string $AppName - application name
     */
    public function __construct($AppName) {

        $this->appName = $AppName;

        $this->config = \OC::$server->getConfig();
        $this->logger = \OC::$server->getLogger();
    }

    public function GetSystemValue($key, $system = false) {
        if ($system) {
            return $this->config->getSystemValue($key);
        }
        if (!empty($this->config->getSystemValue($this->appName))
            && array_key_exists($key, $this->config->getSystemValue($this->appName))) {
            return $this->config->getSystemValue($this->appName)[$key];
        }
        return null;
    }

    public function SetLicenceKey($licenceKey) {
        $licenceKey = trim($licenceKey);

        $this->logger->info("SetLicenceKey: $licenceKey", ["app" => $this->appName]);

        $this->config->setAppValue($this->appName, $this->_licencekey, $licenceKey);
    }

    public function GetLicenceKey() {
    
        $licence = $this->config->getAppValue($this->appName, $this->_licencekey, "");
        if (empty($licence)) {
            $licence = $this->GetSystemValue($this->_licencekey);
        }
        return $licence;
    }

    public function SetSkin($skin) {
        $skin = trim($skin);

        $this->logger->info("SetSkin: $skin", ["app" => $this->appName]);

        $this->config->setAppValue($this->appName, $this->_skin, $skin);
    }

    public function GetSkin() {
    
        $skin = $this->config->getAppValue($this->appName, $this->_skin, "deepblue");
        if (empty($skin)) {
            $skin = $this->GetSystemValue($this->_skin);
        }
        return $skin;
    }
    

}
