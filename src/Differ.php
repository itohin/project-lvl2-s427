<?php

namespace Gendiff\Differ;

function genDiff($firstFile, $secondFile)
{
    $dataBefore = getDataFromFile($firstFile);
    $dataAfter = getDataFromFile($secondFile);
    
    $keys = array_keys(array_merge($dataBefore, $dataAfter));

    $result = array_reduce($keys, function ($acc, $key) use ($dataBefore, $dataAfter) {
        if (array_key_exists($key, $dataBefore) && array_key_exists($key, $dataAfter)) {
            if ($dataBefore[$key] === $dataAfter[$key]) {
                $acc['  ' . $key] = $dataBefore[$key];
            } else {
                $acc['+ ' . $key] =  $dataAfter[$key];
                $acc['- ' . $key] = $dataBefore[$key];
            }
        }

        if (!array_key_exists($key, $dataAfter)) {
            $acc['- ' . $key] = $dataBefore[$key];
        }

        if (!array_key_exists($key, $dataBefore)) {
            $acc['+ ' . $key] =  $dataAfter[$key];
        }

        return $acc;
    }, []);
    
    return json_encode($result, JSON_PRETTY_PRINT);
}

function getDataFromFile($file)
{
    if (file_exists($file)) {
        return json_decode(file_get_contents($file), true);
    }
}
