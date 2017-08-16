<?php

namespace App\Stack;

use App\StackFile;
use Illuminate\Http\Response;

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
        $amount = json_decode(Downloaders::downloadPage("{$this->baseurl}/public-share/{$this->shareid}/list?public=true&token={$this->shareid}&type=folder&offset=0&limit=0&dir={$path}"))->amount;
        $nodes = [];

        while ($amount > 0) {
            $nodesSize = count($nodes);
            $json = json_decode(Downloaders::downloadPage("{$this->baseurl}/public-share/{$this->shareid}/list?public=true&token={$this->shareid}&type=folder&offset={$nodesSize}&limit={$amount}&dir={$path}"));
            $nodes = array_merge($nodes, $json->nodes);
            $amount -= count($json->nodes);
        }

        return $nodes;
    }

    /**
     * Sets headers and presents a remote stack file.
     *
     * @param StackFile $file
     * @param bool $dl
     * @return \Illuminate\Http\Response
     */
    public function presentFile(StackFile $file, $dl = false)
    {
        header("accept-ranges: bytes");
        header("content-disposition: " . ($dl ? 'attachment; ' : '') . "filename=\"{$file->name}\"");
        header("content-type: {$file->mimetype}");
        if (isset($file->size)) {
            header("content-length: {$file->size}");
        }

        readfile(cleanUrl($file->file_full));
        die(); die(); die();

        return new Response("", 200, collect(headers_list())->mapWithKeys(function ($item) {
            return [explode(': ', $item)[0] => explode(': ', $item)[1]];
        })->all());
    }

    /**
     * Uploads a file to stack.
     *
     * @param $remotePath
     * @param $localPath
     * @return mixed
     */
    public function uploadFile($remotePath, $localPath)
    {
        $remotePath = cleanUrl($remotePath);
        $file = fopen($localPath, "rb");
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "{$this->baseurl}/remote.php/webdav/{$this->sharefolder}{$remotePath}");
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        curl_setopt($ch, CURLOPT_USERPWD, "{$this->username}:{$this->password}");
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

        curl_setopt($ch, CURLOPT_PUT, true);
        curl_setopt($ch, CURLOPT_INFILE, $file);
        curl_setopt($ch, CURLOPT_INFILESIZE, filesize($localPath));

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}
