<?php

namespace Gendiff\Tests;

use function Gendiff\Differ\genDiff;
use PHPUnit\Framework\TestCase;

class DifferTest extends TestCase
{
    /** @test */
    public function testDiffFromSimplyData()
    {

        $expected = file_get_contents(__DIR__ . '/testsData/diffData');

        $filePath1 = __DIR__ . '/testsData/before.json';
        $filePath2 = __DIR__ . '/testsData/after.json';

        $actual = genDiff($filePath1, $filePath2);

        $this->assertEquals($expected, $actual);

        $filePath1 = __DIR__ . '/testsData/before.yml';
        $filePath2 = __DIR__ . '/testsData/after.yml';

        $actual = genDiff($filePath1, $filePath2);

        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function testDiffFromMultyData()
    {

        $expected = file_get_contents(__DIR__ . '/testsData/diffMultyData');

        $filePath1 = __DIR__ . '/testsData/beforeMulty.json';
        $filePath2 = __DIR__ . '/testsData/afterMulty.json';

        $actual = genDiff($filePath1, $filePath2);

        $this->assertEquals($expected, $actual);

        $filePath1 = __DIR__ . '/testsData/beforeMulty.yml';
        $filePath2 = __DIR__ . '/testsData/afterMulty.yml';

        $actual = genDiff($filePath1, $filePath2);

        $this->assertEquals($expected, $actual);
    }
}
