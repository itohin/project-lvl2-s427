<?php

namespace Gendiff\Parser;
use Symfony\Component\Yaml\Yaml;

function getData($filepath)
{
    $type = pathinfo($filepath, PATHINFO_EXTENSION);
    $data = file_get_contents($filepath);

    $parser = parse($type, $data);

    return $parser;
}

function parseJson($data)
{
    return json_decode($data, true);
}

function parseYaml($data)
{
    return Yaml::parse($data);
}

function parse($type, $data)
{
    if ($type === 'json') {
        return parseJson($data);
    } elseif ($type === 'yml') {
        return parseYaml($data);
    }
}
