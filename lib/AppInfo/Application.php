<?php

declare(strict_types=1);

namespace OCA\Cadviewer\AppInfo;

use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCP\Security\CSP\AddContentSecurityPolicyEvent;
use OCA\Files\Event\LoadAdditionalScriptsEvent;


use OCA\Cadviewer\Controller\SettingsController;
use OCA\Cadviewer\Listeners\CSPListener;
use OCA\Cadviewer\Listeners\LoadScriptsListener;
use OCA\Cadviewer\AppConfig;

use OC\Files\Filesystem;

use Psr\Container\ContainerInterface;
use OCP\Util;


class Application extends App implements IBootstrap {

    /**
     * Application configuration
     *
     * @var AppConfig
     */
    public $appConfig;

	public const APP_NAME = 'cadviewer';

    public function __construct(array $params  = []) {
        parent::__construct(self::APP_NAME, $params);

        $this->appConfig = new AppConfig(self::APP_NAME);
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
		$context->registerEventListener(LoadAdditionalScriptsEvent::class, LoadScriptsListener::class);
    }

    public function boot(IBootContext $context): void {
        
    }
}
