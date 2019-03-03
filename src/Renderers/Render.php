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
        case 'plain':
            return renderPlain($ast);
        case 'json':
            return renderJson($ast);
    }
}
