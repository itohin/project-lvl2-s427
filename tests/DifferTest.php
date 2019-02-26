<?php

namespace Gendiff\Tests;

use function Gendiff\Differ\genDiff;
use PHPUnit\Framework\TestCase;

class DifferTest extends TestCase
{
    /** @test */
    public function testDiffFromFiles()
    {

        $expected = file_get_contents(__DIR__ . '/testsData/diffData');

        $beforeData = __DIR__ . '/testsData/before.json';
        $afterData = __DIR__ . '/testsData/after.json';

        $actual = genDiff($beforeData, $afterData);

        $this->assertEquals($expected, $actual);

        $beforeData = __DIR__ . '/testsData/before.yml';
        $afterData = __DIR__ . '/testsData/after.yml';

        $actual = genDiff($beforeData, $afterData);

        $this->assertEquals($expected, $actual);
    }
}
