<?php
class Scheme_Lib_Base extends Scheme_Lib_Abstract {
    
    public function quote(Scheme_Env $env, array $args) {
        $this->requireExactly(1, $args);
        return $args[0];
    }
    
    public function letrec(Scheme_Env $env, array $args) {
        $this->requireExactly(2, $args);
        $bindings = $args[0];
        $body = $args[1];
        
        $interpreter = $env->getInterpreter();
        
        $newEnv = $env->createChildEnv();
        $this->requireList($bindings);
        $initedBindings = array();
        foreach ($bindings->listToArray() as $binding) {
            $this->requireList($binding);
            $bindingArray = $binding->listToArray();
            $this->requireExactly(2, $bindingArray);
            
            $var = $bindingArray[0];
            $expr = $bindingArray[1];
            
            $this->requireSymbol($var);
            
            $value = $interpreter->evaluate($newEnv, $expr);
            $initedBindings[$var->value] = $value;
        }
        
        $newEnv->bindAll($initedBindings);
        
        return new Scheme_TailCall($body, $newEnv);
    }
    
    public function lambda(Scheme_Env $env, array $args) {
        $this->requireAtLeast(2, $args);
        $argList = $args[0];
        $body = $args[1];
        
        $argNames = array();
        if ($argList instanceof Scheme_ListVal) {
            foreach ($argList->listToArray() as $i => $value) {
                if ($value instanceof Scheme_Symbol) {
                    $argNames[$i] = $value->value;
                } else {
                    $this->throwBadArgsForLambda();
                }
            }
        } elseif ($argList instanceof Scheme_Symbol) {
            throw new Exception("Variable-length argument lists not yet supported");
        } else {
            $this->throwBadArgsForLambda();
        }
        
        return new Scheme_Lambda($env, $argNames, $body);
    }
    
    private function throwBadArgsForLambda(Scheme_Value $argList) {
        throw new Scheme_Error("Invalid argument list to lambda: `" . $argList->toString() . "`");
    }
    
    
    // TODO: move logic and list stuff out of here
    
    public function ifStmt(Scheme_Env $env, array $args) {
        if (count($args) === 2) {
            $args[] = new Scheme_Unspecified();
        }
        $this->requireExactly(3, $args);
        $cond = $env->getInterpreter()->evaluate($env, $args[0]);
        $next = ($this->isTruthy($cond) ? $args[1] : $args[2]);
        return new Scheme_TailCall($next, $env);
    }
    
    
    public function isEqual(array $args) {
        $this->requireExactly(2, $args);
        $left = $args[0];
        $right = $args[1];
        
        $result = null;
        while ($result === null) {
            if (get_class($left) === get_class($right)) {
                if ($left instanceof Scheme_Bool ||
                    $left instanceof Scheme_Int ||
                    $left instanceof Scheme_Float ||
                    $left instanceof Scheme_String ||
                    $left instanceof Scheme_Symbol) {
                    $result = $left->value == $right->value;
                } elseif ($left instanceof Scheme_Pair) {
                    $left = $left->cdr;
                    $right = $right->cdr;
                } elseif ($left instanceof Scheme_Null) {
                    $result = true;
                } else {
                    $result = false;
                }
            } else {
                $result = false;
            }
        }
        
        return new Scheme_Bool($result);
    }
    
    public function not(array $args) {
        $this->requireExactly(1, $args);
        return new Scheme_Bool(!$this->isTruthy($args[0]));
    }
    
    
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
    
    
    protected function getSpecialFormMethods() {
        return array('quote', 'letrec', 'lambda', 'ifStmt');
    }
    
    protected function mapMethodName($methodName) {
        if ($methodName == 'ifStmt') {
            return 'if';
        } else {
            return parent::mapMethodName($methodName);
        }
    }
}