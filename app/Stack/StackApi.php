<?php

namespace App\Stack;

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
     * @return mixed
     */
    public function downloadStackList($path)
    {
        return json_decode(Downloaders::downloadPage("{$this->baseurl}/public-share/{$this->shareid}/list?public=true&token={$this->shareid}&type=folder&offset=0&limit=0&dir={$path}"));
    }
}
