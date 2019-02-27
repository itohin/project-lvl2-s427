<?php

namespace Gendiff\Parser;
use Symfony\Component\Yaml\Yaml;

function getData($filepath)
{
    $fileType = pathinfo($filepath, PATHINFO_EXTENSION);
    $data = file_get_contents($filepath);

    $parser = parse($fileType);

    return $parser($data);
}

function parse($fileType)
{
    if ($fileType === 'json') {
        return function ($data) {
            return json_decode($data, true);
        };
    } elseif ($fileType === 'yml') {
        return function ($data) {
            return Yaml::parse($data);
        };
    }
}
