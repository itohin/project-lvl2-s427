<?php

namespace Gendiff\Renderers;

function prettyRender($ast, $indentSize = 0)
{
    $indent = str_repeat('    ', $indentSize);

    $result = array_map(function ($node) use ($indent, $indentSize) {

        $status = $node['status'];
        $key = $node['key'];
        $oldValue = $node['oldValue'];
        $newValue = $node['newValue'];
        $data = $node['data'];

        switch ($status) {
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
            case 'data':
                return $indent . '    ' . $key . ': ' . prettyRender($data, $indentSize + 1);
                break;
        }
    }, $ast);
    $output = implode(PHP_EOL, $result) . PHP_EOL;
    return '{' . PHP_EOL . $output . $indent . '}';
}

function dataToString($data, $indent)
{
    if (is_array($data)) {
        $keys = array_keys($data);
        $result = array_reduce($keys, function ($acc, $key) use ($data, $indent) {
            $acc[] = '        ' . $indent . $key . ': ' . $data[$key];
            return $acc;
        }, []);
        $string = implode(PHP_EOL, $result) . PHP_EOL;
        return '{' . PHP_EOL . $string . $indent . '    }';
    } else {
        return $data;
    }
}
