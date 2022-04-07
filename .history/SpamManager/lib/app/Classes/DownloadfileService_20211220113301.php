<?php

namespace WHMCS\Module\Addon\ChatManager\app\Classes;

class DownloadfileService
{
    public $path;
    public $contentType;

    public function __construct(string $path, string $contentType = 'application/octet-stream')
    {
        $this->path = $path;
        $this->contentType = $contentType;
    }
    public function setHeaders()
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        header('Content-Type: ' . finfo_file($finfo, $this->path));
        finfo_close($finfo);
        header('Content-Type: application/octet-stream');
        //Use Content-Disposition: attachment to specify the filename
        header('Content-Disposition: attachment; filename=' . basename($this->path));

        //No cache
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        //Define file size
        header('Content-Length: ' . filesize($this->path));

        return $this;
    }
    public function downloadInit()
    {
        ob_clean();
        flush();
        readfile($this->path);
    }
    public function deleteFile()
    {
        @unlink($this->path);
    }
}
