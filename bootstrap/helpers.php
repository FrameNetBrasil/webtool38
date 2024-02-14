<?php

use Illuminate\Support\Facades\Log;

if (!function_exists('ddump')) {
    function ddump(mixed ...$var)
    {
        foreach ($var ?? [] as $m) {
            $message = print_r($m, true);
            Log::channel('trace')->debug($message);
        }
    }

    function debug(...$vars): void
    {
        $backtrace = debug_backtrace(2, 2);
        $source = last(explode('\\', $backtrace[1]['class'] ?? $backtrace[1]['function'] ?? 'UNKNOWN'));
        $line = $backtrace[0]['line'] ?? '';
        foreach ($vars as $var) {
            Log::debug("", ['dump' => print_r($var, true), 'source' => "$source:$line"]);
        }
    }

    function debugQuery($message, $bindings): void
    {
        $backtrace = debug_backtrace(2);
        $source = last(explode('\\', $backtrace[15]['class'] ?? $backtrace[15]['function'] ?? 'UNKNOWN'));
        $line = $backtrace[14]['line'] ?? '';
        Log::debug($message, ['dump' => $bindings, 'source' => "$source:$line"]);
    }
}
