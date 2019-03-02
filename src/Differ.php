<?php

namespace Gendiff\Differ;
use function Gendiff\Parser\getData;
use function Gendiff\Renderers\render;

function genDiff($firstFilePath, $secondFilePath, $format)
{
    $dataBefore = getData($firstFilePath);
    $dataAfter = getData($secondFilePath);

    $ast = createAst($dataBefore, $dataAfter);
    $output = render($ast, $format);

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
                $acc[] = createNode($key, 'node', null, null, createAst($firstValue, $secondValue));
            } elseif (is_array($firstValue) || is_array($secondValue)) {
                $acc[] = createNode($key, 'unchanged', $firstValue, $secondValue);
            } else {
                if ($firstValue == $secondValue) {
                    $acc[] = createNode($key, 'unchanged', $firstValue, null);
                } else {
                    $acc[] = createNode($key, 'changed', $firstValue, $secondValue);
                }
            }
        } else {
            if (array_key_exists($key, $dataBefore)) {
                $acc[] = createNode($key, 'removed', $firstValue, null);
            } else {
                $acc[] = createNode($key, 'added', null, $secondValue);
            }
        }
        return $acc;
    }, []);
}

function createNode($key, $type, $oldValue, $newValue, $children = null)
{
    $node = [
        'key' => $key,
        'type' => $type,
        'oldValue' => $oldValue,
        'newValue' => $newValue,
        'children' => $children
    ];

    return $node;
}
