#!/usr/bin/env php
<?php

namespace CLI\genDiff;

/// Файл для функций, которые я буду использовать в CLI

function genDiff(string $pathToFile1, string $pathToFile2): string
{
    if (file_exists($pathToFile1)) {
        $file1 = json_decode(file_get_contents($pathToFile1), true);
    } else {
        $file1 = json_decode($pathToFile1, true);
    }

    if (file_exists($pathToFile2)) {
        $file2 = json_decode(file_get_contents($pathToFile2), true);
    } else {
        $file2 = json_decode($pathToFile2, true);
    }

    if ($file1 === null || $file2 === null) {
        throw new \Exception("Invalid JSON input.");
    }

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
