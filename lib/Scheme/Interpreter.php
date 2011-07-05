<?php
class Scheme_Interpreter {
    
    /**
     * @return Scheme_Env
     */
    public function createEnv(Scheme_Env $parent = null) {
        return new Scheme_Env($this, $parent);
    }
    
    public function evaluate(Scheme_Env $env, Scheme_Value $expr) {
        while (true) {
            if ($expr instanceof Scheme_Symbol) {
                return $env->get($expr->value);
            } elseif ($expr instanceof Scheme_Pair) {
                $result = $this->apply($env, $expr->car, $expr->cdr->listToArray());
                if ($result instanceof Scheme_TailCall) {
                    $expr = $result->expr;
                    $env = $result->env !== null ? $result->env : $env;
                } else {
                    assert('$result instanceof Scheme_Value');
                    return $result;
                }
            } else {
                return $expr;
            }
        }
    }
    
    private function apply(Scheme_Env $env, Scheme_Value $value, array $args) {
        $value = $this->evaluate($env, $value);
        if ($value instanceof Scheme_SpecialForm) {
            return $value->evaluate($env, $args);
        } elseif ($value instanceof Scheme_PhpCallback) {
            $args = $this->evaluateEach($env, $args);
            return $value->evaluate($env, $args);
        } else {
            throw new Scheme_Error("Not a callable value: " . $value->toString());
        }
    }
    
    private function evaluateEach(Scheme_Env $env, array $vals) {
        foreach ($vals as $key => $val) {
            $vals[$key] = $this->evaluate($env, $val);
        }
        return $vals;
    }
}