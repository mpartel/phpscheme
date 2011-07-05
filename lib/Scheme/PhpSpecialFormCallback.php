<?php
class Scheme_PhpSpecialFormCallback implements Scheme_SpecialForm {
    private $callback;
    private $name;
    
    public function __construct($callback, $name = null) {
        $this->callback = $callback;
        $this->name = $name;
    }
    
    public function evaluate(Scheme_Env $env, array $args) {
        return call_user_func($this->callback, $args, $env);
    }
    
    public function toString() {
        return $this->name ? "<special form `$this->name`>" : "<special form>";
    }
}