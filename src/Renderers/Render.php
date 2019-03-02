<?php

namespace Gendiff\Renderers;
use function Gendiff\Renderers\prettyRender;
use function Gendiff\Renderers\plainRender;
use function Gendiff\Renderers\jsonRender;

function render($ast, $format)
{
    if ($format === 'pretty') {
        return prettyRender($ast);
    } elseif ($format === 'plain') {
        return plainRender($ast);
    } elseif ($format === 'json') {
        return jsonRender($ast);
    }
}
