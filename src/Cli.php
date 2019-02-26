<?php

namespace Gendiff\Cli;

use Docopt;
use function Gendiff\Differ\genDiff;

function run($doc)
{
    $args = Docopt::handle($doc);

    $firstFile = $args['<firstFile>'];
    $secondFile = $args['<secondFile>'];

    $diff = genDiff($firstFile, $secondFile);

    print_r($diff);
}
