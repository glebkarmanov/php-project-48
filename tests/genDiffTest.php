<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use function CLI\genDiff\genDiff;

// Класс UtilsTest наследует класс TestCase
// Имя класса совпадает с именем файла
class genDiffTest extends TestCase
{
    public function testGenDiff(): void
    {

        $json1 = file_get_contents('tests/fixtures/file1.json');
        $json2 = file_get_contents('tests/fixtures/file2.json');
        // Сначала идет ожидаемое значение (expected)
        // И только потом актуальное (actual)
        $this->assertEquals('{
  host: hexlet.io
- timeout: 50
+ timeout: 20
- proxy: "123.234.53.22"
- follow: false
+ verbose: 1
}', genDiff($json1, $json2));
    }

    public function testGenDiffIdenticalFiles(): void
    {
        $json1 = '{"host": "hexlet.io", "timeout": 20}';
        $json2 = '{"host": "hexlet.io", "timeout": 20}';

        $expected = "{
  host: hexlet.io
  timeout: 20
}";
        $this->assertEquals($expected, genDiff($json1, $json2));
    }

    public function testGenDiffDifferentValues(): void
    {
        $json1 = '{"host": "hexlet.io", "timeout": 50}';
        $json2 = '{"host": "hexlet.io", "timeout": 20}';

        $expected = "{
  host: hexlet.io
- timeout: 50
+ timeout: 20
}";
        $this->assertEquals($expected, genDiff($json1, $json2));
    }

    public function testGenDiffKeyAddedAndRemoved(): void
    {
        $json1 = '{"host": "hexlet.io", "timeout": 50}';
        $json2 = '{"host": "hexlet.io", "timeout": 20, "verbose": 1}';

        $expected = "{
  host: hexlet.io
- timeout: 50
+ timeout: 20
+ verbose: 1
}";
        $this->assertEquals($expected, genDiff($json1, $json2));
    }

    public function testGenDiffEmptyFiles(): void
    {
        $json1 = '{}';
        $json2 = '{}';

        $expected = "{\n\n}";
        $this->assertEquals($expected, genDiff($json1, $json2));
    }

    public function testGenDiffInvalidJson(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid JSON input.");

        $invalidJson = '{"host": "hexlet.io"';
        $json2 = '{"host": "hexlet.io"}';

        genDiff($invalidJson, $json2);
    }
}
