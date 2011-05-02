<?php

function my_autoload($name) {
    require_once str_replace('_', '/', $name) . '.php';
}

spl_autoload_register('my_autoload');

new Scheme_Pair(new Scheme_Symbol("Hei"), new Scheme_Null);
$parser = new Scheme_Parser();

function testParser($a, $b) {
    $parser = new Scheme_Parser();
    if ($parser->parse($a)->toString() != $b) {
        $x = $parser->parse($a)->toString();
        echo "VIRHE: $a   ->   $b   ($x)\n";
    } else {
        echo "OK: $a   ->   $b\n";
    }
}

//echo $parser->parse("(* (+ 1 9) (+ 5 2))");
testParser("(* (+ 1 9) (+ 5 2))", "(* (+ 1 9) (+ 5 2))");

