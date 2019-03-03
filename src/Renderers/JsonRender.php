<?php

namespace Gendiff\Renderers;

function renderJson($ast)
{
    return json_encode($ast, JSON_PRETTY_PRINT + JSON_NUMERIC_CHECK);
}
