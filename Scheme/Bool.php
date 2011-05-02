<?php
class Scheme_Bool implements Scheme_Form {
    public $value;
    
    public function __construct($value) {
        $this->value = (bool)$value;
    }
}

