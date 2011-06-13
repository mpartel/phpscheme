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
    
    
    protected function getSpecialFormMethods() {
        return array('quote', 'letrec', 'lambda');
    }
}