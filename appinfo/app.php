<?php

namespace OCA\Cadviewer\AppInfo;

use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCP\Security\CSP\AddContentSecurityPolicyEvent;


use OCA\Cadviewer\Controller\SettingsController;
use OCA\Cadviewer\Listeners\CSPListener;
use OCA\Cadviewer\AppConfig;

use OC\Files\Filesystem;

use Psr\Container\ContainerInterface;
use OCP\Util;

$eventDispatcher = \OC::$server->getEventDispatcher();

$eventDispatcher->addListener('OCA\Files::loadAdditionalScripts', function(){
    Util::addScript('cadviewer', 'cadviewer-main' );
    Util::addStyle('cadviewer', 'style' );
});

class Application extends App implements IBootstrap {

    /**
     * Application configuration
     *
     * @var AppConfig
     */
    public $appConfig;

    public function __construct(array $urlParams = []) {
        $appName = "cadviewer";
        parent::__construct($appName, $urlParams);

        $this->appConfig = new AppConfig($appName);
    }

    public function register(IRegistrationContext $context): void {
        
        $context->registerService("L10N", function (ContainerInterface $c) {
            return $c->get("ServerContainer")->getL10N($c->get("AppName"));
        });

        $context->registerService("RootStorage", function (ContainerInterface $c) {
            return $c->get("ServerContainer")->getRootFolder();
        });

        $context->registerService("UserSession", function (ContainerInterface $c) {
            return $c->get("ServerContainer")->getUserSession();
        });

        $context->registerService("UserManager", function (ContainerInterface $c) {
            return $c->get("ServerContainer")->getUserManager();
        });

        $context->registerService("Logger", function (ContainerInterface $c) {
            return $c->get("ServerContainer")->getLogger();
        });

        $context->registerService("URLGenerator", function (ContainerInterface $c) {
            return $c->get("ServerContainer")->getURLGenerator();
        });

        // Controllers
        $context->registerService("SettingsController", function (ContainerInterface $c) {
            return new SettingsController(
                $c->get("AppName"),
                $c->get("Request"),
                $c->get("URLGenerator"),
                $c->get("L10N"),
                $c->get("Logger"),
                $this->appConfig
            );
        });

    
		$context->registerEventListener(AddContentSecurityPolicyEvent::class, CSPListener::class);

    }

    public function boot(IBootContext $context): void {
        
    }
}
