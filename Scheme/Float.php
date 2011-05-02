<?php
class Scheme_Float implements Scheme_Form {
    public $value;
    
    public function __construct($value) {
        $this->value = (double)$value;
    }

    public function toString() {
        return (string)$this->value;
    }
}

