<?php
class Scheme_TailCall {
    public $expr;
    public $env;
    
    public function __construct(Scheme_Value $expr, Scheme_Env $env = null) {
        $this->expr = $expr;
        $this->env = $env;
    }
}