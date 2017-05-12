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
    $slugify = new \Cocur\Slugify\Slugify(['regexp' => '/([^A-Za-z0-9\.\-])+/']);
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
