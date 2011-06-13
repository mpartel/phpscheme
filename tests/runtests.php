<?php
error_reporting(E_ALL | E_NOTICE);

set_include_path(
    dirname(dirname(__FILE__)) . '/lib' . PATH_SEPARATOR .
    dirname(__FILE__) . '/lib'
);

function myAutoload($name) {
    require_once str_replace('_', '/', $name) . '.php';
}

spl_autoload_register('myAutoload');

$reporter = new TestReporter();

$phpRunner = new TestRunner_Php($reporter);
$phpRunner->runAllTests(dirname(__FILE__) . '/misc');

$schemeRunner = new TestRunner_Scheme($reporter);
$schemeRunner->runAllTests(dirname(__FILE__) . '/misc');

$reporter->printSummary();
