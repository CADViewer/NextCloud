<?php

namespace OCA\Cadviewer\Migration;

require \OC::$SERVERROOT . "/3rdparty/autoload.php";

use OCP\Files\IMimeTypeLoader;
use OCP\Migration\IOutput;
use OCP\Migration\IRepairStep;
use OC\Core\Command\Maintenance\Mimetype\UpdateJS;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\ConsoleOutput;

class RegisterMimeType extends MimeTypeMigration
{
    public function getName()
    {
        return 'Register MIME types for Cadviewer';
    }

    private function registerForExistingFiles()
    {
        $mimeTypeId = $this->mimeTypeLoader->getId('application/acad');
        $this->mimeTypeLoader->updateFilecache('dwg', $mimeTypeId);

        $mimeTypeId = $this->mimeTypeLoader->getId('application/dxf');
        $this->mimeTypeLoader->updateFilecache('dxf', $mimeTypeId);

        $mimeTypeId = $this->mimeTypeLoader->getId('application/x-dwf');
        $this->mimeTypeLoader->updateFilecache('dwf', $mimeTypeId);

        $mimeTypeId = $this->mimeTypeLoader->getId('application/dgn');
        $this->mimeTypeLoader->updateFilecache('dgn', $mimeTypeId);

    }

    private function registerForNewFiles()
    {
        $configDir = \OC::$configDir;
        $mimetypealiasesFile = $configDir . self::CUSTOM_MIMETYPEALIASES;
        $mimetypemappingFile = $configDir . self::CUSTOM_MIMETYPEMAPPING;

        $this->appendToFile($mimetypealiasesFile, [
            'application/acad' => 'dwg',
            'application/dxf' => 'dxf',
            'application/x-dwf' => 'dwf',
            'application/dgn' => 'dgn'
        ]);
        $this->appendToFile($mimetypemappingFile, [
            'dwg' => ['application/acad'], 
            'dxf' => ['application/dxf'],
            'dwf' => ['application/x-dwf'],
            'dgn' => ['application/dgn']
        ]);
    }

    private function copyIcons()
    {
        $icons = ['dwg', 'dxf', 'dwf', 'dgn'];

        foreach ($icons as $icon) 
        {
            $source = __DIR__ . '/../../img/cvlogo.svg';
            $target = \OC::$SERVERROOT . '/core/img/filetypes/' . $icon . '.svg';
            if (!file_exists($target) || md5_file($target) !== md5_file($source)) 
            {
                copy($source, $target);
            }
        }
    }

    public function run(IOutput $output)
    {
        $output->info('Registering the mimetype...');

        // Register the mime type for existing files
        $this->registerForExistingFiles();

        // Register the mime type for new files
        $this->registerForNewFiles();

        $output->info('The mimetype was successfully registered.');

        $output->info('Copy cadviewer icons to core/img directory.');
        $this->copyIcons();

        $this->updateJS->run(new StringInput(''), new ConsoleOutput());
    }

    private function appendToFile(string $filename, array $data) {
        $obj = [];
        if (file_exists($filename)) {
            $content = file_get_contents($filename);
            $obj = json_decode($content, true);
        }
        foreach ($data as $key => $value) {
            $obj[$key] = $value;
        }
        file_put_contents($filename, json_encode($obj,  JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
    }
}