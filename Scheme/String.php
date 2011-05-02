<?php
class Scheme_String implements Scheme_Value {
    public $value;
    
    public function __construct($value) {
        $this->value = (string)$value;
    }

    public function toString() {
        return "\"" . addslashes($this->value) . "\"";
    }
}
