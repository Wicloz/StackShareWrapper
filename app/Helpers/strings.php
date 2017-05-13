<?php

/**
 * @param $haystack
 * @param $needle
 * @return bool
 */
function str_starts_with($haystack, $needle)
{
    // search backwards starting from haystack length characters from the end
    return $needle === '' || strrpos($haystack, $needle, -strlen($haystack)) !== false;
}

/**
 * @param $haystack
 * @param $needle
 * @return bool
 */
function str_ends_with($haystack, $needle)
{
    // search forward starting from end minus needle length characters
    return $needle === '' || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
}

/**
 * @param string $string
 * @param string $start
 * @param string $end
 * @param bool $lastStart
 * @param bool $lastEnd
 * @return bool|string
 */
function str_get_between($string, $start = null, $end = null, $lastStart = false, $lastEnd = false)
{
    if (empty($start)) {
        $ini = 0;
    }
    elseif ($lastStart) {
        $ini = strrpos($string, $start);
    }
    else {
        $ini = strpos($string, $start);
    }

    if ($ini === false) return false;
    $ini += strlen($start);

    if (empty($end)) {
        $len = $ini;
    }
    elseif ($lastEnd) {
        $len = strrpos($string, $end, $ini);
    }
    else {
        $len = strpos($string, $end, $ini);
    }

    if ($len === false) return false;
    $len -= $ini;

    return substr($string, $ini, $len);
}
