<?php

namespace Gendiff\Renderers;
use function Gendiff\Renderers\renderPretty;
use function Gendiff\Renderers\renderPlain;
use function Gendiff\Renderers\renderJson;

function render($ast, $format)
{
    switch ($format) {
        case 'pretty':
            return renderPretty($ast);
            break;
        case 'plain':
            return renderPlain($ast);
            break;
        case 'json':
            return renderJson($ast);
            break;
    }
}
