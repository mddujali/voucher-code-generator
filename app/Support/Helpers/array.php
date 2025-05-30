<?php

declare(strict_types=1);

use Illuminate\Support\Collection;

if (!function_exists('array_flatten')) {
    function array_flatten(array $array): array
    {
        $result = [];

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = [...$result, ...array_flatten($value)];
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }
}


if (!function_exists('array_equal')) {
    function array_equal(array $default, array $defined): bool
    {
        $defaultCollection = Collection::make($default)->sort()->values();
        $definedCollection = Collection::make($defined)->sort()->values();

        return $defaultCollection->all() === $definedCollection->all();
    }
}
