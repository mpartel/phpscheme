<?php
class Scheme_InterpreterWithMetrics extends Scheme_Interpreter
{
    private $maxStackDepth = 0;
    private $stackDepth = 0;
    
    public function evaluate($env, $value) {
        ++$this->stackDepth;
        if ($this->stackDepth > $this->maxStackDepth) {
            $this->maxStackDepth = $this->stackDepth;
        }
        
        try {
            $ret = parent::evaluate($env, $value);
        } catch (Exception $e) {
            --$this->stackDepth;
            throw $e;
        }
        --$this->stackDepth;
        
        return $ret;
    }
    
    public function __get($var) {
        if (isset($this->$var)) {
            return $this->$var;
        } else {
            throw new Exception("No such metric");
        }
    }
}