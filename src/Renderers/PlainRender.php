<?php

namespace Gendiff\Renderers;

function plainRender($data, $parent = null)
{
    $result = array_map(function ($node) use ($parent) {
        $type = $node['type'];
        $key = $node['key'];
        $oldValue = is_bool($node['oldValue']) ? var_export($node['oldValue'], 1) : $node['oldValue'];
        $newValue = is_bool($node['newValue']) ? var_export($node['newValue'], 1) : $node['newValue'];
        $newValue = is_array($newValue) ? "complex value" : $newValue;
        $children = $node['children'];

        $fullKey = $parent ? "'{$parent}.{$key}'" : "'{$key}'";

        switch ($type) {
            case 'changed':
                return "Property {$fullKey} was changed. From {$oldValue} to {$newValue}";
                break;
            case 'removed':
                return "Property {$fullKey} was removed";
                break;
            case 'added':
                return "Property {$fullKey} was added with value: '{$newValue}'";
                break;
            case 'children':
                return plainRender($children, $key);
                break;
        }
    }, $data);

    $result = array_filter($result);
    $output = implode(PHP_EOL, $result);
    return $output;
}
