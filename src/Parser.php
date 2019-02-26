<?php

namespace Gendiff\Parser;
use Symfony\Component\Yaml\Yaml;

function parse($filepath)
{
    $extention = pathinfo($filepath, PATHINFO_EXTENSION);

    if ($extention === 'json') {
        $data = parseJson($filepath);
    } elseif ($extention === 'yml') {
        $data = parseYaml($filepath);
    }

    return $data;
}

function parseJson($filepath)
{
    return json_decode(file_get_contents($filepath), true);
}

function parseYaml($filepath)
{
    return Yaml::parse(file_get_contents($filepath));
}