<?php

if (!function_exists('LocaleSettings')) {
    function LocaleSettings() {
        return '';
    }
}

if (!function_exists('Theme')) {
    function Theme() {
        // Return default theme settings or empty string to prevent error
        return '';
    }
}

if (!function_exists('format_date')) {
    function format_date($date, $format = 'd M, Y') {
        if (!$date) return '';
        return \Carbon\Carbon::parse($date)->format($format);
    }
}