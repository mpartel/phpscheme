<?php
class Scheme_Symbol implements Scheme_Value {
    public $name;
    
    public function __construct($name) {
        $this->name = $name;
    }

    public function toString() {
        return $this->name;
    }
}
