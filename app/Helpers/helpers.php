<?php

use Pekkis\MimeTypes\MimeTypes;

/**
 * @param $string
 * @return string
 */
function htmlentities_decode($string)
{
    return html_entity_decode(preg_replace_callback("/(&#[0-9]+;)/", function ($m) {
        return mb_convert_encoding($m[1], "UTF-8", "HTML-ENTITIES");
    }, $string));
}

/**
 * @param $string
 * @return string
 */
function slugify($string)
{
    $slugify = new \Cocur\Slugify\Slugify(['regexp' => '/([^A-Za-z0-9\\-\\.])+/']);
    return $slugify->slugify($string);
}

/**
 * @param $string
 * @return string
 */
function hashify($string)
{
    return hash('crc32b', $string);
}

/**
 * @param $filename
 * @return null|string
 */
function extensionToMimeType($extension)
{
    $mt = new MimeTypes();
    $mimetype = $mt->extensionToMimeType(mb_strtolower($extension));
    if (!empty($mimetype)) {
        return $mimetype;
    }
    return 'application/octet-stream';
}

/**
 * @param $url
 * @param bool $spacesToPlus
 * @return mixed
 */
function cleanUrl($url, $spacesToPlus = false)
{
    if ($spacesToPlus) {
        $url = str_replace(' ', '+', $url);
    }
    else {
        $url = str_replace(' ', '%20', $url);
    }

    return preg_replace_callback('/([^a-zA-Z0-9\\%\\=\\:\\+\\&\\?\\-\\_\\.\\,\\;\\/\\\\])/u', function ($matches) {
        return urlencode($matches[1]);
    }, $url);
}

/**
 * @param $size
 * @param int $decimals
 * @param string $unit
 * @return string
 */
function humanFileSize($size, $decimals = 2, $unit = '')
{
    if ((empty($unit) && $size >= 1 << 30) || $unit == 'GB')
        return number_format($size / (1 << 30), $decimals) . 'GB';
    if ((empty($unit) && $size >= 1 << 20) || $unit == 'MB')
        return number_format($size / (1 << 20), $decimals) . 'MB';
    if ((empty($unit) && $size >= 1 << 10) || $unit == 'KB')
        return number_format($size / (1 << 10), $decimals) . 'KB';
    return number_format($size) . ' bytes';
}

/**
 * @param \Illuminate\Http\Request $request
 * @return string
 */
function encodeRequestToGet(\Illuminate\Http\Request $request)
{
    $get = '';

    if (count($request->all()) > 0) {
        $collection = collect($request->all());

        $collection->transform(function ($item, $key) {
            return [$key, $item];
        });

        $collection = $collection->values();

        $get .= $collection->reduce(function ($carry, $item) {
            $sep = ($carry == null ? '?' : '&');
            return "{$carry}{$sep}{$item[0]}={$item[1]}";
        });
    }

    return $get;
}
