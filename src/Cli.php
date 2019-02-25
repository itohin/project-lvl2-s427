<?php

namespace Gendiff;

function run($doc)
{
    $args = \Docopt::handle($doc);
    foreach ($args as $k=>$v)
        echo $k.': '.json_encode($v).PHP_EOL;
}