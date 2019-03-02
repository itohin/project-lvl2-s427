<?php

namespace Gendiff\Renderers;

function renderJson($ast)
{
    $filteredAst = astFilter($ast);
    return json_encode($filteredAst, JSON_PRETTY_PRINT + JSON_NUMERIC_CHECK);
}

function astFilter($ast)
{
    $ast = array_filter($ast);
    foreach ($ast as &$node) {
        if (is_array($node)) {
            $node = astFilter($node);
        }
    }

    return $ast;
}
