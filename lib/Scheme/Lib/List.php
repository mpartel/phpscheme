<?php
class Scheme_Lib_List extends Scheme_Lib_Abstract {
    public function cons(array $args) {
        $this->requireExactly(2, $args);
        return new Scheme_Pair($args[0], $args[1]);
    }
    
    public function car(array $args) {
        $this->requireExactly(1, $args);
        $this->requireNonemptyList($args[0]);
        return $args[0]->car;
    }
    
    public function cdr(array $args) {
        $this->requireExactly(1, $args);
        $this->requireNonemptyList($args[0]);
        return $args[0]->cdr;
    }
    
    public function isNull(array $args) {
        $this->requireExactly(1, $args);
        return new Scheme_Bool($args[0] instanceof Scheme_Null);
    }
}