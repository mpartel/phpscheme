<?php
class Scheme_PhpCallback implements Scheme_Value {
    private $callback;
    private $name;
    
    public function __construct($callback, $name = null) {
        $this->callback = $callback;
        $this->name = $name;
    }
    
    public function evaluate(array $args) {
        return call_user_func($this->callback, $args);
    }
    
    public function toString() {
        return $this->name ? "<builtin func `$this->name`>" : "<builtin func>";
    }
}