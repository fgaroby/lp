<?php

/**
 * Convert units, depending its size.
 *
 * @return string
 */
if (!function_exists('convert_units')) {
    function convert_units(int $size): string
    {
        $unit = ['o', 'ko', 'Mo', 'Go', 'To', 'Po'];

        return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
    }
}
