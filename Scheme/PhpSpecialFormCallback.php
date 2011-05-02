<?php
class Scheme_PhpSpecialFormCallback implements Scheme_SpecialForm {
    private $callback;
    
    public function __construct($callback) {
        $this->callback = $callback;
    }
    
    public function makeActivation(Scheme_Env $execEnv, array $args) {
        array_unshift($execEnv, $args);
        return call_user_func_array($this->callback, $args);
    }
    
    public function toString() {
        return "<special form>";
    }
}