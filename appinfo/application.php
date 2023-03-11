<?php

namespace OCA\Cadviewer\AppInfo;

use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCP\Dashboard\RegisterWidgetEvent;
use OCP\DirectEditing\RegisterDirectEditorEvent;
use OCA\Files\Event\LoadAdditionalScriptsEvent;

use OCA\Files_Sharing\Event\BeforeTemplateRenderedEvent;
use OCA\Viewer\Event\LoadViewer;


use OCA\Cadviewer\Controller\SettingsController;
use OCA\Cadviewer\AppConfig;

use OC\Files\Filesystem;

use Psr\Container\ContainerInterface;
use OCP\Util;

$eventDispatcher = \OC::$server->getEventDispatcher();

$eventDispatcher->addListener('OCA\Files::loadAdditionalScripts', function(){
    Util::addScript('cadviewer', 'cadviewer-main' );
    Util::addStyle('cadviewer', 'style' );

    Util::addStyle('cadviewer','cadviewer-bootstrap');
    Util::addStyle('cadviewer','cadviewer-core-styles');

//    Util::addStyle('cadviewer', 'bootstrap-multiselect');
//    Util::addStyle('cadviewer', 'cvjs_7');
    // Util::addStyle('cadviewer', 'cadviewer_bootstrap.min');
    // Util::addStyle('cadviewer', 'font-awesome.min');
    // Util::addStyle('cadviewer', 'jquery.qtip.min');
//     Util::addStyle('cadviewer', 'jquery-ui-1.11.4.min');
//     Util::addStyle('cadviewer', 'cvjs_jquery.qtip.min');

//    Util::addStyle('cadviewer', 'bootstrap-cadviewer');


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

    
        // $context->registerEventListener(LoadAdditionalScriptsEvent::class, FilesListener::class);
        // $context->registerEventListener(RegisterDirectEditorEvent::class, DirectEditorListener::class);
        // $context->registerEventListener(LoadViewer::class, ViewerListener::class);
        // $context->registerEventListener(BeforeTemplateRenderedEvent::class, FileSharingListener::class);
        // $context->registerEventListener(RegisterWidgetEvent::class, WidgetListener::class);

        // if (interface_exists("OCP\Files\Template\ICustomTemplateProvider")) {
        //     $context->registerTemplateProvider(TemplateProvider::class);
        // }

    }

    public function boot(IBootContext $context): void {
        
    }
}
