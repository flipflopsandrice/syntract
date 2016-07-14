<?php

namespace Thorongil\Syntract\Receiver;

use Distill\Distill;
use Thorongil\Syntract\Exception\ExtractException;
use Thorongil\Syntract\Log;
use Thorongil\Syntract\Receiver\Util\ArchiveInfo;

/**
 * Class Extract
 *
 * @package Thorongil\Syntract\Receiver
 */
class Extract extends AbstractReceiver
{
    /** Class constants */
    //@todo refactor to ARCHIVE_TYPES reusable classes
    const ARCHIVE_TYPE_RAR = 'application/x-rar';
    const ARCHIVE_TYPE_ZIP = 'application/zip';
    const ARCHIVE_TYPES    = [
        self::ARCHIVE_TYPE_RAR,
        self::ARCHIVE_TYPE_ZIP
    ];

    /**
     * @var array
     */
    private $config;

    /**
     * @var Log
     */
    private $log;

    /**
     * @var string
     */
    private $folder;

    /**
     * @var Distill
     */
    private $extractor;

    /**
     * Extract constructor
     *
     * @param array $config
     * @param Log   $log
     *
     * @throws ExtractException
     */
    public function __construct($config = [], Log $log)
    {
        $this->log    = $log;
        $this->config = $config;

        if (!isset($config['folder']) || empty($config['folder'])) {
            throw new ExtractException(ExtractException::EXCEPTION_SETTING_MISSING, ['folder']);
        }

        if (!file_exists($config['folder']) || !is_dir($config['folder'])) {
            throw new ExtractException(ExtractException::EXCEPTION_INVALID_FOLDER, [$config['folder']]);
        }

        $this->folder    = $config['folder'];
        $this->extractor = new Distill();
    }

    /**
     * Get list of all unextracted archives
     */
    public function getQueue()
    {
        $files = [];
        $this->scanForArchives($this->folder, $files);

        if (empty($files)) {
            $this->log->addInfo('No archives found', ['folder' => $this->folder]);
        }

        return $files;
    }

    /**
     * Execute the extraction
     *
     * @param array
     * @return array
     */
    public function extract($files = [])
    {
        $this->log->addDebug('Extract started', $this->config);

        $extracted = [];

        foreach ($files as $file) {
            if ($this->extractArchive($file)) {
                $extracted[] = $file;
            }
        }
        return $extracted;
    }

    /**
     * Clean the archives
     *
     * @param array
     * @return bool
     */
    public function clean($files = [])
    {
        if (!empty($this->config['keep_archives'])) {
            $this->log->addDebug('Skipping clean');

            return false;
        }

        foreach ($files as $file) {
            $this->log->addInfo('Removed archive', ['file' => $file]);
            unlink($file);
            //@todo also remove related archives, for RAR eg: .r01, .r02, etc
        }
        return true;
    }

    /**
     * @param $file
     * @return null|array
     * @todo refactor into ArchiveInfo class
     */
    private function getArchiveContents($file)
    {
        $info = new ArchiveInfo($file);

        return $info->getContents();
    }

    /**
     * @param $file
     * @return boolean
     */
    private function extractArchive($file)
    {
        //@todo reuse mimetype on all places, perhaps from ArchiveInfo class?
        $mimeType = mime_content_type($file);
        $contents = $this->getArchiveContents($file);

        if (!empty($contents)) {
            $firstFile = dirname($file) . DIRECTORY_SEPARATOR . $contents[0];
            if (file_exists($firstFile)) {
                $this->log->addDebug('Skipping extraction, already extracted', ['file' => $file, 'mimeType' => $mimeType, 'firstFile' => $firstFile]);
                return true;
            }
        }

        $this->log->addDebug('Extracting archive', ['file' => $file, 'mimeType' => $mimeType]);

        try {
            $this->extractor->extract($file, dirname($file));
            return true;
        } catch (\Exception $e) {
            //@todo handle exception
            return false;
        }
    }


    /**
     * @param $directory
     * @param $files
     *
     * @todo refactor into an ArchiveScanner and MimeFilter classes
     */
    private function scanForArchives($directory, &$files)
    {
        $exclude = ['.', '..'];
        $hits    = scandir($directory);

        foreach ($hits as $hit) {
            if (in_array($hit, $exclude)) {
                continue;
            }

            $path = $directory . DIRECTORY_SEPARATOR . $hit;

            if (is_dir($path)) {
                $this->scanForArchives($path, $files);
                continue;
            }

            $mimeType = mime_content_type($path);
            if (!in_array($mimeType, self::ARCHIVE_TYPES)) {
                continue;
            }

            $files[] = $path;
        }
    }
}
