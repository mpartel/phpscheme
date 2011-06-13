<?php
class Scheme_TailCall {
    public $env;
    public $expr;
    
    public function __construct(Scheme_Env $env, Scheme_Value $expr) {
        $this->env = $env;
        $this->expr = $expr;
    }
}