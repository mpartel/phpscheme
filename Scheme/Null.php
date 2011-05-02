<?php
class Scheme_Null implements Scheme_Value, Scheme_ListVal {
    public function __construct() {
    }
    
    public function listToArray() {
        return array();
    }

    public function toString() {
        return "()";
    }
}
