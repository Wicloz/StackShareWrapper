<?php

namespace App\Stack;

class Downloaders
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
        $url = cleanUrl($url);

        if ($tries >= 8) {
            throw new \Exception("Download Failed: '{$url}' after {$tries} tries.");
        }

        else {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            $response = htmlentities_decode(curl_exec($ch));
            curl_close($ch);
        }

        if (!isset($response)) {
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
    public static function downloadStackList($path)
    {
        $baseurl = config('stack.baseurl');
        $shareid = config('stack.shareid');

        return json_decode(self::downloadPage("{$baseurl}/public-share/{$shareid}/list?public=true&token={$shareid}&type=folder&offset=0&limit=0&dir={$path}"));
    }

    /**
     * Gets the size for the requested remote file as defined in the 'content-length' header.
     *
     * @param $url
     * @return float|null
     */
    public static function getFileSize($url) {
        $url = cleanUrl($url);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
        curl_close($ch);

        if (is_int($size) && $size >= 0) {
            return $size;
        } else {
            return null;
        }
    }
}
