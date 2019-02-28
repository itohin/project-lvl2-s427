<?php

namespace Gendiff\Parser;
use Symfony\Component\Yaml\Yaml;

function getData($filepath)
{
    $type = pathinfo($filepath, PATHINFO_EXTENSION);
    $data = file_get_contents($filepath);

    $parser = parser($type);

    return $parser($data);
}

function parser($type)
{
    if ($type === 'json') {
        return function ($data) {
            return json_decode($data, true);
        };
    } elseif ($type === 'yml') {
        return function ($data) {
            return Yaml::parse($data);
        };
    }
}
