<?php

namespace OCA\Cadviewer;

use OCP\IURLGenerator;
use OCP\Settings\IIconSection;

class AdminSection implements IIconSection {

    private $urlGenerator;

    public function __construct(IURLGenerator $urlGenerator) {
        $this->urlGenerator = $urlGenerator;
    }

    public function getIcon() {
        return $this->urlGenerator->imagePath("cadviewer", "cvlogo.png");
    }

    public function getID() {
        return "cadviewer";
    }

    public function getName() {
        return "CADViewer";
    }

    public function getPriority() {
        return 50;
    }
}
