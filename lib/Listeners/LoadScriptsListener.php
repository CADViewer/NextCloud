<?php

declare(strict_types=1);

namespace OCA\Cadviewer\Listeners;

use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;

use OCA\Files\Event\LoadAdditionalScriptsEvent;

use OCP\Util;

class LoadScriptsListener implements IEventListener  {


    public function handle(Event $event): void {
        if (!($event instanceOf LoadAdditionalScriptsEvent)) {
            return;
        }

        $plugin_base_path = str_replace('lib/Listeners', '',dirname(__FILE__));
        $path_to_script = str_replace(getcwd(), '', $plugin_base_path);
        
        $current_dir = $plugin_base_path."appinfo";
        $init_file = $current_dir.'/cadviewer_init.txt';
        $init_file_content = file_get_contents($init_file);
        if ($init_file_content != 'initialized') {
            
            // Loop over files in /js/ folder with extension .js or .map.js and replace all occurences of /assets/cadviewer/ with /apps/cadviewer/assets/
            $files = glob($plugin_base_path.'js/*.js');
            $files = array_merge($files, glob($plugin_base_path.'js/*.js.map'));
            foreach($files as $file) {
                $content = file_get_contents($file);
                $content = str_replace('/assets/cadviewer/', $path_to_script.'assets/', $content);
                file_put_contents($file, $content);
            }

            $folder = "apps";

            // check extra-apps in $_SERVER['REQUEST_URI']
            if (strpos($_SERVER['REQUEST_URI'], "extra-apps") !== false) {
                $folder = "extra-apps";
            }

            // check custom_apps in $_SERVER['REQUEST_URI']
            if (strpos($_SERVER['REQUEST_URI'], "custom_apps") !== false) {
                $folder = "custom_apps";
            }

            $files = glob($plugin_base_path.'settings/js/*.js');
            $files = array_merge($files, glob($plugin_base_path.'js/*.js'));
            foreach($files as $file) {
                $content = file_get_contents($file);
                $content = str_replace('OC.generateUrl("apps/"', 'OC.generateUrl("'.$folder.'/"', $content);
                file_put_contents($file, $content);
            }

            file_put_contents($init_file, 'initialized');
        }

        Util::addScript('cadviewer', 'cadviewer-main' );
        Util::addStyle('cadviewer', 'style' );
    }

}