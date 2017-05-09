<?php

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
