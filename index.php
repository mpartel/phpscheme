<?php

error_reporting(E_ALL | E_NOTICE);

set_include_path(dirname(__FILE__) . '/lib');

function my_autoload($name) {
    require_once str_replace('_', '/', $name) . '.php';
}

spl_autoload_register('my_autoload');

$parser = new Scheme_Parser();

function testParser($a, $b) {
    $parser = new Scheme_Parser();
    if ($parser->parse($a)->toString() != $b) {
        $x = $parser->parse($a)->toString();
        echo "ERROR: $a   ->   $x   (expected $b)\n";
    } else {
        echo "OK:    $a   ->   $b\n";
    }
}

function testInterpreter($code, $expected, array $envVars = array()) {
    
    $rootEnv = new Scheme_Env();
    
    $lib = new Scheme_Lib_Math();
    $lib->bindToEnv($rootEnv);
    
    $rootEnv->bindAll($envVars);
    
    $parser = new Scheme_Parser();
    $expr = $parser->parse($code);
    
    $interpreter = new Scheme_Interpreter();
    $result = $interpreter->evaluate($rootEnv, $expr);
    
    $result = $result->toString();
    if ($result != $expected) {
        echo "VIRHE: $code   ->   $result   (expected $expected)\n";
    } else {
        echo "OK:    $code   ->   $result\n";
    }
}

testParser("(* (+ 1 9) (+ 5 2))", "(* (+ 1 9) (+ 5 2))");
testInterpreter("(+ x 10)", "13", array('x' => new Scheme_Int(3)));
testInterpreter("(+ x x)", "6", array('x' => new Scheme_Int(3)));
testInterpreter("(+ (+ x x) (+ 2 2))", "10", array('x' => new Scheme_Int(3)));
