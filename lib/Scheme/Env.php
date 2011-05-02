<?php
class Scheme_Env {
    private $parent;
    private $vars;
    
    public function __construct(Scheme_Env $parent = null) {
        $this->parent = $parent;
        $this->vars = array();
    }
    
    public function getParentEnv() {
        return $this->parent;
    }
    
    public function bind($var, $binding) {
        if (isset($vars[$var])) {
            throw new Scheme_Error("Already bound: '$var'");
        }
        $this->vars[$var] = $binding;
    }
    
    public function bindAll(array $bindings) {
        foreach ($bindings as $key => $value) {
            $this->bind($key, $value);
        }
    }
    
    public function get($var) {
        $e = $this;
        while ($e != null) {
            if ($e->hasLocalBinding($var)) {
                return $e->getLocalBinding($var);
            }
            $e = $e->getParentEnv();
        }
        throw new Scheme_Error("Unbound variable '$var'");
    }
    
    public function hasLocalBinding($var) {
        return isset($this->vars[$var]);
    }
    
    public function getLocalBinding($var) {
        return $this->vars[$var];
    }
}