<?php

if (!function_exists('filter_var_boolean')) {
    function filter_var_boolean($value)
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}

if (!function_exists('filter_var_email')) {
    function filter_var_email($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }
}
