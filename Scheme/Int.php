<?php
class Scheme_Int implements Scheme_Form {
    public $value;
    
    public function __construct($value) {
        $this->value = (string)$value;
    }
}

