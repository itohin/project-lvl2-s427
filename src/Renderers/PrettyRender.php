<?php

namespace Gendiff\Renderers;

function renderPretty($ast, $depth = 0)
{
    $indent = str_repeat('    ', $depth);

    $result = array_map(function ($node) use ($indent, $depth) {

        $type = $node['type'];
        $key = $node['key'];
        $oldValue = booleanToString($node['oldValue']);
        $newValue = booleanToString($node['newValue']);
        $children = $node['children'];

        switch ($type) {
            case 'unchanged':
                return  $indent . '    ' . $key . ': ' . dataToString($oldValue, $indent);
                break;
            case 'changed':
                return $indent . '  + ' . $key . ': ' . dataToString($newValue, $indent) . PHP_EOL .
                    $indent . '  - ' . $key . ': ' . dataToString($oldValue, $indent);
                break;
            case 'removed':
                return $indent . '  - ' . $key . ': ' . dataToString($oldValue, $indent);
                break;
            case 'added':
                return $indent . '  + ' . $key . ': ' . dataToString($newValue, $indent);
                break;
            case 'node':
                return $indent . '    ' . $key . ': ' . renderPretty($children, $depth + 1);
                break;
        }
    }, $ast);
    $output = implode(PHP_EOL, $result) . PHP_EOL;
    return '{' . PHP_EOL . $output . $indent . '}';
}

function dataToString($data, $indent)
{
    if (empty($data)) {
        return null;
    }

    if (!is_array($data)) {
        return $data;
    }

        $keys = array_keys($data);
        $result = array_reduce($keys, function ($acc, $key) use ($data, $indent) {
            $acc[] = '        ' . $indent . $key . ': ' . $data[$key];
            return $acc;
        }, []);
        $string = implode(PHP_EOL, $result) . PHP_EOL;
        return '{' . PHP_EOL . $string . $indent . '    }';
}

function booleanToString($item)
{
    return is_bool($item) ? var_export($item, 1) : $item;
}
