<?php

function str_starts_with($haystack, $needle) {
    // search backwards starting from haystack length characters from the end
    return $needle === '' || strrpos($haystack, $needle, - strlen($haystack)) !== false;
}

function str_ends_with($haystack, $needle) {
    // search forward starting from end minus needle length characters
    return $needle === '' || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
}
