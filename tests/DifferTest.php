<?php

namespace Gendiff\Tests;

use function Gendiff\Differ\genDiff;
use PHPUnit\Framework\TestCase;
use function Gendiff\Differ\getDataFromFile;

class DifferTest extends TestCase
{
    /** @test */
    public function testDataFromFile()
    {
        $json = '{
          "host": "hexlet.io",
          "timeout": 50,
          "proxy": "123.234.53.22"
        }';
        $file = __DIR__ . '/testsData/before.json';

        $expected = json_decode($json, true);
        $actual = getDataFromFile($file);

        $this->assertIsArray($actual);
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function testDiffFromFiles()
    {

        $expected = <<<EOL
{
    "  host": "hexlet.io",
    "+ timeout": 20,
    "- timeout": 50,
    "- proxy": "123.234.53.22",
    "+ verbose": true
}
EOL;


        $before = __DIR__ . '/testsData/before.json';
        $after = __DIR__ . '/testsData/after.json';

        $actual = genDiff($before, $after);

        $this->assertIsString($actual);
        $this->assertEquals($expected, $actual);
    }
}
