<?php

namespace Gendiff\Cli;

use Docopt;
use function Gendiff\Differ\genDiff;

function run($doc)
{
    $args = Docopt::handle($doc);

    $firstFile = $args['<firstFile>'];
    $secondFile = $args['<secondFile>'];
    $format = $args['--format'];

    $diff = genDiff($firstFile, $secondFile, $format);

    print_r($diff);
}
