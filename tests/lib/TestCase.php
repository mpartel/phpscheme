<?php
class TestCase {
    public function assertTrue($cond) {
        if (!$cond) {
            throw new Exception("Assertion failure");
        }
    }
    
    public function assertEquals($expected, $actual) {
        if ($expected != $actual) {
            throw new Exception("Expected $expected == $actual");
        }
    }
}