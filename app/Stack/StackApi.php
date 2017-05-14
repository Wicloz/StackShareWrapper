<?php

namespace App\Stack;

use App\StackFile;

class StackApi
{
    protected $baseurl;
    protected $shareid;
    protected $sharefolder;
    protected $username;
    protected $password;

    /**
     * StackApi constructor.
     *
     * @param $baseurl
     * @param $shareid
     * @param $sharefolder
     * @param $username
     * @param $password
     */
    public function __construct($baseurl, $shareid, $sharefolder, $username, $password)
    {
        $this->baseurl = $baseurl;
        $this->shareid = $shareid;
        $this->sharefolder = $sharefolder;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Downloads the JSON description from stack for a requested folder.
     *
     * @param $path
     * @return string
     */
    public function getFolderInfo($path)
    {
        return json_decode(Downloaders::downloadPage("{$this->baseurl}/public-share/{$this->shareid}/list?public=true&token={$this->shareid}&type=folder&offset=0&limit=0&dir={$path}"));
    }

    /**
     * Gets the size of the requested stack file as defined in the 'content-length' header.
     *
     * @param $path
     * @return string|null
     */
    public function getFileSize($path)
    {
        $url = cleanUrl("{$this->baseurl}/remote.php/webdav/{$this->sharefolder}/{$path}");

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERPWD, "{$this->username}:{$this->password}");
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
        curl_close($ch);

        if (is_numeric($size) && $size >= 0) {
            return $size;
        } else {
            return null;
        }
    }

    /**
     * Sets headers and presents a remote stack file.
     *
     * @param StackFile $file
     * @param bool $dl
     */
    public function presentFile(StackFile $file, $dl = false) {
        header("accept-ranges: bytes");
        header("content-disposition: " . ($dl ? 'attachment; ' : '') . "filename=\"{$file->name}\"");
        header("content-type: {$file->mimetype}");
        if (isset($file->size)) {
            header("content-length: {$file->size}");
        }
        readfile(cleanUrl($file->file_full_remote));
    }

    /**
     * Sets headers and presents a thumbnail for a remote stack file.
     *
     * @param StackFile $file
     */
    public function presentThumbnail(StackFile $file) {
        header("accept-ranges: bytes");
        header("content-disposition: filename=\"preview\"");
        header("content-type: {$file->mimetype}");
        readfile(cleanUrl($file->file_thumbnail_remote));
    }
}
