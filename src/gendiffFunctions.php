#!/usr/bin/env php
<?php

/// Файл для функций, которые я буду использовать в CLI

function genDiff(string $pathToFile1, string $pathToFile2): string
{
    // Тут у нас 2 массива ассоциативных массива
    $file1 = json_decode(file_get_contents($pathToFile1), true);
    $file2 = json_decode(file_get_contents($pathToFile2), true);

    // Теперь получим уникальные ключи с обоих массивов
    $allKeys = array_unique(array_merge(array_keys($file1), array_keys($file2)));

    $result = [];
    $startElement = ['{'];
    $endElement = ['}'];

    foreach ($allKeys as $key) {
        $inFile1 = array_key_exists($key, $file1);
        $inFile2 = array_key_exists($key, $file2);

        if ($inFile1 == $inFile2) {
            if ($file1[$key] == $file2[$key]) {
                $value = $file1[$key];
                $result[] = "  $key: $value";
            } else {
                $value1 = $file1[$key];
                $value2 = $file2[$key];
                $result[] = "- $key: $value1";
                $result[] = "+ $key: $value2";
            }
        } elseif ($inFile1) {
            $value = $file1[$key];
            $result[] = sprintf('- %s: %s', $key, json_encode($value));
        } else {
            $value = $file2[$key];
            $result[] = "+ $key: $value";
        }
    }

    $formattedResult = "{\n";
    $formattedResult .= implode("\n", $result);
    $formattedResult .= "\n}";

    return $formattedResult;
}

//$pathToFile1 = $argv[1];
//$pathToFile2 = $argv[2];

//$result = genDiff($pathToFile1, $pathToFile2);
//echo $result . "\n";
