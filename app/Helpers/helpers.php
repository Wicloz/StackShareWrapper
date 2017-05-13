<?php

use Pekkis\MimeTypes\MimeTypes;

/**
 * @param $string
 * @return string
 */
function htmlentities_decode($string) {
    return html_entity_decode(preg_replace_callback("/(&#[0-9]+;)/", function($m) {
        return mb_convert_encoding($m[1], "UTF-8", "HTML-ENTITIES");
    }, $string));
}

/**
 * @param $string
 * @return string
 */
function slugify($string) {
    $slugify = new \Cocur\Slugify\Slugify();
    return $slugify->slugify($string);
}

/**
 * @param $string
 * @return string
 */
function hashify($string) {
    return hash('crc32b', $string);
}

/**
 * @param $filename
 * @return null|string
 */
function filenameToMimeType($filename) {
    $bits = explode('.', $filename);

    if (count($bits) > 1) {
        $mt = new MimeTypes();
        return $mt->extensionToMimeType(mb_strtolower($bits[count($bits) - 1]));
    }

    else {
        return 'application/octet-stream';
    }
}

/**
 * @param $url
 * @param bool $spacesToPlus
 * @return mixed
 */
function cleanUrl($url, $spacesToPlus = true) {
    if ($spacesToPlus) {
        $url = str_replace(' ', '+', $url);
    }
    return preg_replace_callback('/([^a-zA-Z0-9\\%\\=\\:\\+\\&\\?\\-\\_\\/\\\\])/u', function($matches) {
        return urlencode($matches[1]);
    }, $url);
}
