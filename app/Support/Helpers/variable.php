<?php

if (!function_exists('isset_value')) {
    function isset_value(&$value, $default = null): mixed
    {
        return $value ?? $default;
    }
}
