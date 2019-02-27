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
                $acc[] = ['status' => 'data', 'key' => $key, 'value' => genDiff($firstValue, $secondValue)];
            } elseif ($firstValue === $secondValue) {
                $acc[] = ['status' => 'unchanged', 'key' => $key, 'value' => $firstValue];
            } else {
                $acc[] =  ['status' => 'added', 'key' => $key, 'value' => $secondValue];
                $acc[] = ['status' => 'deleted', 'key' => $key, 'value' => $firstValue];
            }
        }

        if (!array_key_exists($key, $dataAfter)) {
            $acc[] = ['status' => 'deleted', 'key' => $key, 'value' => $firstValue];
        }

        if (!array_key_exists($key, $dataBefore)) {
            $acc[] =  ['status' => 'added', 'key' => $key, 'value' => $secondValue];
        }

        return $acc;
    }, []);
}

function makeOutput($ast)
{
    $data = array_reduce($ast, function ($acc, $item) {
        if (is_bool($item['value'])) {
            $item['value'] = true ? 'true' : 'false';
        }
        switch ($item['status']) {
            case 'unchanged':
                $acc[] = "    {$item['key']}: {$item['value']}";
                break;
            case 'deleted':
                $acc[] = "  - {$item['key']}: {$item['value']}";
                break;
            case 'added':
                $acc[] = "  + {$item['key']}: {$item['value']}";
                break;
        }

        return $acc;
    }, []);

    $output = "{" . PHP_EOL . implode(PHP_EOL, $data) .  PHP_EOL . "}";

    return $output;
}
