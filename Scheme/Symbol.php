<?php
class Scheme_Symbol implements Scheme_Form {
    public $name;
    
    public function __construct($name) {
        $this->name = $name;
    }

    public function toString() {
        return $this->name;
    }
}
