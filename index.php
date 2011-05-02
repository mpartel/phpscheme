<?php

function my_autoload($name) {
    require_once str_replace('_', '/', $name) . '.php';
}

spl_autoload_register('my_autoload');

new Scheme_Pair(new Scheme_Symbol("Hei"), new Scheme_Null);
$parser = new Scheme_Parser();

