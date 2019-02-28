<?php

namespace Gendiff\Differ;
use function Gendiff\Parser\getData;

function genDiff($firstFilePath, $secondFilePath)
{
    $dataBefore = getData($firstFilePath);
    $dataAfter = getData($secondFilePath);

    $ast = createAst($dataBefore, $dataAfter);
    $output = makeOutput($ast);

    return $output;
}

function createAst($dataBefore, $dataAfter)
{
    $keys = array_unique(array_merge(array_keys($dataBefore), array_keys($dataAfter)));
    return array_reduce($keys, function ($acc, $key) use ($dataBefore, $dataAfter) {
        $firstValue = isset($dataBefore[$key]) ? $dataBefore[$key] : null;
        $secondValue = isset($dataAfter[$key]) ? $dataAfter[$key] : null;

        if (array_key_exists($key, $dataBefore) && array_key_exists($key, $dataAfter)) {
            if (is_array($firstValue) && is_array($secondValue)) {
                $acc[] = createNode('data', $key, $firstValue, null, createAst($firstValue, $secondValue));
            } elseif (is_array($firstValue) || is_array($secondValue)) {
                $acc[] = createNode('unchanged', $key, $firstValue, $secondValue);
            } else {
                if ($firstValue == $secondValue) {
                    $acc[] = createNode('unchanged', $key, $firstValue, null);
                } else {
                    $acc[] = createNode('changed', $key, $firstValue, $secondValue);
                }
            }
        } else {
            if (array_key_exists($key, $dataBefore)) {
                $acc[] = createNode('removed', $key, $firstValue, null);
            } else {
                $acc[] = createNode('added', $key, null, $secondValue);
            }
        }
        return $acc;
    }, []);
}

function createNode($status, $key, $oldValue, $newValue, $data = null)
{
    $node = [
        'status' => $status,
        'key' => $key,
        'oldValue' => is_bool($oldValue) ? var_export($oldValue, 1) : $oldValue,
        'newValue' => is_bool($newValue) ? var_export($newValue, 1) : $newValue,
        'data' => $data
    ];

    return $node;
}

function makeOutput($data, $indentSize = 0)
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
                return $indent . '    ' . $key . ': ' . makeOutput($data, $indentSize + 1);
                break;
        }
    }, $data);
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
