<?php

namespace Gendiff\Differ;
use function Gendiff\Parser\parse;

function genDiff($firstFile, $secondFile)
{
    $dataBefore = parse($firstFile);
    $dataAfter = parse($secondFile);

    $keys = array_keys(array_merge($dataBefore, $dataAfter));

    $diffData = array_reduce($keys, function ($acc, $key) use ($dataBefore, $dataAfter) {
        if (array_key_exists($key, $dataBefore) && array_key_exists($key, $dataAfter)) {
            if ($dataBefore[$key] === $dataAfter[$key]) {
                $acc[] = ['status' => 'unchanged', 'key' => $key, 'value' => $dataBefore[$key]];
            } else {
                $acc[] =  ['status' => 'added', 'key' => $key, 'value' => $dataAfter[$key]];
                $acc[] = ['status' => 'deleted', 'key' => $key, 'value' => $dataBefore[$key]];
            }
        }

        if (!array_key_exists($key, $dataAfter)) {
            $acc[] = ['status' => 'deleted', 'key' => $key, 'value' => $dataBefore[$key]];
        }

        if (!array_key_exists($key, $dataBefore)) {
            $acc[] =  ['status' => 'added', 'key' => $key, 'value' => $dataAfter[$key]];
        }

        return $acc;
    }, []);

    $output = makeOutput($diffData);

    return $output;
}

function makeOutput($data)
{
    $result = array_reduce($data, function ($acc, $item) {
        if (is_bool($item['value'])) {
            $item['value'] = true ? 'true' : 'false';
        }
        switch ($item['status']) {
            case 'unchanged':
                $acc .= "    {$item['key']}: {$item['value']}" . PHP_EOL;
                break;
            case 'added':
                $acc .= "  + {$item['key']}: {$item['value']}" . PHP_EOL;
                break;
            case 'deleted':
                $acc .= "  - {$item['key']}: {$item['value']}" . PHP_EOL;
                break;
        }

        return $acc;
    }, '');

    $output = '{' . PHP_EOL . $result . '}';

    return $output;
}
