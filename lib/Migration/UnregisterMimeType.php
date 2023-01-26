<?php

namespace OCA\Cadviewer\Migration;


use OCP\Files\IMimeTypeLoader;
use OCP\Migration\IOutput;
use OCP\Migration\IRepairStep;
use OC\Core\Command\Maintenance\Mimetype\UpdateJS;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\ConsoleOutput;

class UnregisterMimeType extends MimeTypeMigration
{
    public function getName()
    {
        return 'Unregister MIME type for Cadviewer';
    }

    private function unregisterForExistingFiles()
    {
        $mimeTypeId = $this->mimeTypeLoader->getId('application/octet-stream');
        $this->mimeTypeLoader->updateFilecache('dwg', $mimeTypeId);
        $this->mimeTypeLoader->updateFilecache('dxf', $mimeTypeId);
        $this->mimeTypeLoader->updateFilecache('dwf', $mimeTypeId);
        $this->mimeTypeLoader->updateFilecache('dgn', $mimeTypeId);
    }

    private function unregisterForNewFiles()
    {
        $configDir = \OC::$configDir;
        $mimetypealiasesFile = $configDir . self::CUSTOM_MIMETYPEALIASES;
        $mimetypemappingFile = $configDir . self::CUSTOM_MIMETYPEMAPPING;

        $this->removeFromFile($mimetypealiasesFile, [
            'application/acad' => 'dwg',
            'application/dxf' => 'dxf',
            'application/x-dwf' => 'dwf',
            'application/dgn' => 'dgn'
        ]);
        $this->removeFromFile($mimetypemappingFile, [
            'dwg' => ['application/acad'], 
            'dxf' => ['application/dxf'],
            'dwf' => ['application/x-dwf'],
            'dgn' => ['application/dgn']
        ]);

        $this->updateJS->run(new StringInput(''), new ConsoleOutput());
    }

    public function run(IOutput $output)
    {
        $output->info('Unregistering the mimetype...');

        // Register the mime type for existing files
        $this->unregisterForExistingFiles();

        // Register the mime type for new files
        $this->unregisterForNewFiles();

        $output->info('The mimetype was successfully unregistered.');
    }

    private function removeFromFile(string $filename, array $data) {
        $obj = [];
        if (file_exists($filename)) {
            $content = file_get_contents($filename);
            $obj = json_decode($content, true);
        }
        foreach ($data as $key => $value) {
            unset($obj[$key]);
        }
        file_put_contents($filename, json_encode($obj,  JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
    }
}