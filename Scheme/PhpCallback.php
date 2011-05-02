<?php
class Scheme_PhpCallback implements Scheme_Form {
    private $callback;
    
    public function __construct($callback) {
        $this->callback = $callback;
    }
    
    public function evaluate(array $args) {
        return call_user_func($this->callback, $args);
    }
    
    public function toString() {
        return "<library function>";
    }
}