<?php

error_reporting(E_ALL);

ini_set('display_errors', '1');

function dd($data)
{
    echo '<br/><div style="
        display: inline-block;
        padding: 0 10px;
        border: 1px dashed gray;
        background: lightgray;
    ">
<pre>';

    print_r([$data]);

    echo "</pre></div><br/>";
}
