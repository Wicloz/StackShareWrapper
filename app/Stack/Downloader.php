<?php

namespace App\Stack;

class Downloader
{
    /**
     * Downloads a requested page with retries and failure checking.
     *
     * @param $url
     * @param int $tries
     * @return string
     * @throws \Exception
     */
    public static function downloadPage($url, $tries = 0)
    {
        $url = preg_replace_callback('/([^a-zA-Z0-9\\%\\=\\:\\+\\&\\?\\-\\_\\/\\\\])/u', function($matches) {
            return urlencode($matches[1]);
        }, $url);

        if ($tries >= 8) {
            throw new \Exception("Download Failed: '{$url}' after {$tries} tries.");
        }

        else {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
            $response = htmlentities_decode(curl_exec($curl));
            curl_close($curl);
        }

        if (empty($response)) {
            $response = self::downloadPage($url, $tries + 1);
        }

        return $response;
    }

    /**
     * Downloads the JSON from stack for a requested folder.
     *
     * @param $path
     * @return mixed
     */
    public static function downloadList($path)
    {
        $baseurl = config('stack.baseurl');
        $shareid = config('stack.shareid');

        return json_decode(self::downloadPage("{$baseurl}/public-share/{$shareid}/list?public=true&token={$shareid}&type=folder&offset=0&limit=0&dir={$path}"));
    }
}
