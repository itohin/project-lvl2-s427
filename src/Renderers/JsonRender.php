<?php

namespace Gendiff\Renderers;

function renderJson($ast)
{
    $filteredAst = array_filter_recursive($ast);
    return json_encode($filteredAst, JSON_PRETTY_PRINT + JSON_NUMERIC_CHECK);
}

function array_filter_recursive($array)
{
    $array = array_filter($array);
    foreach ($array as &$value) {
        if (is_array($value)) {
            $value = array_filter_recursive($value);
        }
    }

    return $array;
}
