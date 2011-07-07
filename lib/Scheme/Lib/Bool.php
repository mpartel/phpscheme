<?php
class Scheme_Lib_Bool extends Scheme_Lib_Abstract {
    
    public function ifStmt(array $args, Scheme_Env $env) {
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
                    if (!$this->isTruthy($this->isEqual(array($left->car, $right->car)))) {
                        $result = false;
                        break;
                    }
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
    
    
    protected function getSpecialFormMethods() {
        return array('ifStmt');
    }
    
    protected function mapMethodName($methodName) {
        if ($methodName == 'ifStmt') {
            return 'if';
        } else {
            return parent::mapMethodName($methodName);
        }
    }
}