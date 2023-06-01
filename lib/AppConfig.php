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
     * The axlic licence key for autoexchange
     *
     * @var string
     */
    private $_axlic_licencekey = "AxlicLicenceKey";

    /**
     * The skin for cadviewer icons
     * 
     * @var string
     */
    private $_skin = "Skin";

    /**
     * The cadviewer converters frontend parameters
     * 
     * @var string
     */
    private $_line_weight_factors = "LineWeightFactors";

    /**
     * The cadviewer converters parameters
     * 
     * @var string
     */
    private $_parameters = "Parameters";

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

    public function SetAxlicLicenceKey($axlicLicenceKey) {
        $axlicLicenceKey = trim($axlicLicenceKey);

        $this->logger->info("SetAxlicLicenceKey: $axlicLicenceKey", ["app" => $this->appName]);

        $this->config->setAppValue($this->appName, $this->_axlic_licencekey, $axlicLicenceKey);
    }

    public function GetAxlicLicenceKey() {
    
        $axlic_licence = $this->config->getAppValue($this->appName, $this->_axlic_licencekey, "");
        if (empty($axlic_licence)) {
            $axlic_licence = "";
        }
        return $axlic_licence;
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

    public function SetLineWeightFactors($lineWeightFactors) {
        $lineWeightFactors =  trim($lineWeightFactors);

        $this->logger->info("SetLineWeightFactors: $lineWeightFactors", ["app" => $this->appName]);

        $this->config->setAppValue($this->appName, $this->_line_weight_factors, $lineWeightFactors);
    }

    public function GetLineWeightFactors() {
        
        $lineWeightFactors = $this->config->getAppValue($this->appName, $this->_line_weight_factors, '[
            {
                "user_frontend": "*",
                "folder_frontend": "*",
                "value_frontend": "100"
            }
        ]');
        if (empty($lineWeightFactors)) {
            $lineWeightFactors = $this->GetSystemValue($this->_line_weight_factors);
        }
        return $lineWeightFactors;
    }


    public function SetParameters($parameters) {
        $parameters = trim($parameters);

        $this->logger->info("SetParameters: $parameters", ["app" => $this->appName]);

        $this->config->setAppValue($this->appName, $this->_parameters, $parameters);
    }

    public function GetParameters() {
    
        $parameters = $this->config->getAppValue($this->appName, $this->_parameters, '[
            {
                "parameter_conversion": "strokea",
                "folder_conversion": "*",
                "user_conversion": "*",
                "value_conversion": ""
            },
            {
                "parameter_conversion": "last",
                "folder_conversion": "*",
                "user_conversion": "*",
                "value_conversion": ""
            },
            {
                "parameter_conversion": "extents",
                "folder_conversion": "*",
                "user_conversion": "*",
                "value_conversion": ""
            },
        ]');
        if (empty($parameters)) {
            $parameters = '[
                {
                    "parameter_conversion": "strokea",
                    "folder_conversion": "*",
                    "user_conversion": "*",
                    "value_conversion": ""
                },
                {
                    "parameter_conversion": "last",
                    "folder_conversion": "*",
                    "user_conversion": "*",
                    "value_conversion": ""
                },
                {
                    "parameter_conversion": "extents",
                    "folder_conversion": "*",
                    "user_conversion": "*",
                    "value_conversion": ""
                },
            ]';
            $this->SetParameters($parameters);
        } else {
            if (isset($parameters["parameter_1"])){ 
                $parameters_tmp = array();

                for ($i = 1; $i < 10; $i++) {
                    if (isset($parameters["parameter_".$i]) && !empty($parameters["parameter_".$i])) {
                        $parameters_tmp[] = array("parameter_conversion"=> $parameters["parameter_".$i], "value_conversion" => $parameters["value_".$i], "folder_conversion" => "*");
                    }
                }
                if (count($parameters_tmp) > 0){
                    $parameters  = json_encode($parameters_tmp);
                }else  {
                    $parameters = '[
                        {
                            "parameter_conversion": "strokea",
                            "folder_conversion": "*",
                            "user_conversion": "*",
                            "value_conversion": ""
                        },
                        {
                            "parameter_conversion": "last",
                            "folder_conversion": "*",
                            "user_conversion": "*",
                            "value_conversion": ""
                        },
                        {
                            "parameter_conversion": "extents",
                            "folder_conversion": "*",
                            "user_conversion": "*",
                            "value_conversion": ""
                        },
                    ]';
                }
                $this->SetParameters($parameters);
            }
        }     
        // var_dump(json_decode(stripslashes($parameters), true));
        // var_dump(json_last_error());
        // die();    
        return $parameters;
    }
    
    

}
