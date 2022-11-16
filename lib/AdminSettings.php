<?php
namespace OCA\Cadviewer;

use OCP\Settings\ISettings;

use OCA\Cadviewer\AppInfo\Application;
use OCA\Cadviewer\Controller\SettingsController;


class AdminSettings implements ISettings {

    public function __construct() {
    }


    public function getForm() {
        $app = \OC::$server->query(Application::class);
        $container = $app->getContainer();
        $response = $container->query(SettingsController::class)->index();
        return $response;
    }

    public function getSection() {
        return "cadviewer";
    }

    public function getPriority() {
        return 50;
    }
}
