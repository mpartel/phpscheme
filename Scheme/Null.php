<?php
class Scheme_Null implements Scheme_Form, Scheme_ListVal {
    public function __construct() {
    }
    
    public function listToArray() {
        return array();
    }
}
