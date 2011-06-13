<?php
class Scheme_Symbol implements Scheme_Value {
    public $value;
    
    public function __construct($value) {
        $this->value = $value;
    }

    public function toString() {
        return $this->value;
    }
}
