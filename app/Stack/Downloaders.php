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
}
