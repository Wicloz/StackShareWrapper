<?php

namespace App\Stack;

class Downloaders
{
    /**
     * Downloads a requested page with retries and failure checking.
     *
     * @param $url
     * @param string $username
     * @param string $password
     * @param int $tries
     * @return string
     * @throws \Exception
     */
    public static function downloadPage($url, $username = null, $password = null, $tries = 0)
    {
        $url = cleanUrl($url);

        if ($tries >= 8) {
            throw new \Exception("Download Failed: '{$url}' after {$tries} tries.");
        }

        else {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            if (isset($username) && isset($password)) {
                curl_setopt($ch, CURLOPT_USERPWD, "{$username}:{$password}");
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            }
            $output = htmlentities_decode(curl_exec($ch));
            $info = curl_getinfo($ch);
            curl_close($ch);
        }

        if (!isset($output) || !in_array(substr($info['http_code'], 0, 1), ['1', '2', '3'])) {
            $output = self::downloadPage($url, $tries + 1);
        }

        return $output;
    }
}
