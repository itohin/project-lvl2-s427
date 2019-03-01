<?php

namespace Gendiff\Renderers;
use function Gendiff\Renderers\prettyRender;
use function Gendiff\Renderers\plainRenderRender;

function render($ast, $format)
{
    if ($format === 'pretty') {
        return prettyRender($ast);
    } elseif ($format === 'plain') {
        return plainRender($ast);
    }
}
