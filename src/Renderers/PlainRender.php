<?php

namespace Gendiff\Renderers;

function plainRender($data, $parent = null)
{
    $result = array_map(function ($node) use ($parent) {
        $status = $node['status'];
        $key = $node['key'];
        $oldValue = $node['oldValue'];
        $newValue = is_array($node['newValue']) ? "complex value" : $node['newValue'];
        $data = $node['data'];

        $fullKey = $parent ? "'{$parent}.{$key}'" : "'{$key}'";

        switch ($status) {
            case 'changed':
                return "Property {$fullKey} was changed. From {$oldValue} to {$newValue}";
                break;
            case 'removed':
                return "Property {$fullKey} was removed";
                break;
            case 'added':
                return "Property {$fullKey} was added with value: '{$newValue}'";
                break;
            case 'data':
                return plainRender($data, $key);
                break;
        }
    }, $data);

    $result = array_filter($result);
    $output = implode(PHP_EOL, $result);
    return $output;
}
