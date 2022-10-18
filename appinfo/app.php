<?php
/**
 * Load Javascrip
 */
use OCP\Util;
$eventDispatcher = \OC::$server->getEventDispatcher();
$eventDispatcher->addListener('OCA\Files::loadAdditionalScripts', function(){
    Util::addScript('cadviewer', 'cadviewer-main' );
    Util::addStyle('cadviewer', 'style' );
    Util::addStyle('cadviewer', 'bootstrap-multiselect');
    Util::addStyle('cadviewer', 'cvjs_7');
    // Util::addStyle('cadviewer', 'cadviewer_bootstrap.min');
    // Util::addStyle('cadviewer', 'font-awesome.min');
    // Util::addStyle('cadviewer', 'jquery.qtip.min');
    // Util::addStyle('cadviewer', 'jquery-ui-1.11.4.min');
    // Util::addStyle('cadviewer', 'cvjs_jquery.qtip.min');
    Util::addStyle('cadviewer', 'bootstrap-cadviewer');
});