<?php

namespace Thorongil\Syntract\Receiver\Util;

use Thorongil\Syntract\Exception\ExtractException;
use Thorongil\Syntract\Receiver\Extract;

/**
 * Class ArchiveContents
 *
 * @package Thorongil\Syntract\Receiver\Util
 * @todo refactor into ContentStrategies
 */
class ArchiveInfo
{
    /**
     * @var string
     */
    private $file;

    public function __construct($file)
    {
        $this->file = $file;
    }

    public function getContents()
    {
        $mimetype = mime_content_type($this->file);

        switch($mimetype)
        {
            //@todo refactor const to ARCHIVE_TYPES reusable classes
            case Extract::ARCHIVE_TYPE_RAR:
                return $this->getRarContents();
            case Extract::ARCHIVE_TYPE_ZIP:
            default:
                throw new ExtractException('Unhandled file type');
        }
    }

    private function getRarContents()
    {
        $pattern =  '/(?:([drwx\-]{10})([\ 0-9]{12})([0-9]{4})-([0-9]{2})-([0-9]{2}) )([0-9:]{5})(.*)(\\n)/isU';
        $response = shell_exec('unrar l "' . $this->file. '"');
        preg_match_all($pattern, $response, $matches);

        if (empty($matches) || !isset($matches[7])) {
            return [];
        }
        return array_map('trim', $matches[7]);
    }
}
