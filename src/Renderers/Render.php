<?php

namespace Gendiff\Renderers;
use function Gendiff\Renderers\prettyRender;
use function Gendiff\Renderers\plainRender;
use function Gendiff\Renderers\jsonRender;

function render($ast, $format)
{
    switch ($format) {
        case 'pretty':
            return prettyRender($ast);
            break;
        case 'plain':
            return plainRender($ast);
            break;
        case 'json':
            return jsonRender($ast);
            break;
    }
}
