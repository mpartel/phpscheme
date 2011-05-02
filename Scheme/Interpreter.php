<?php
class Scheme_Interpreter {
    
    public function evaluate(Scheme_Env $env, Scheme_Form $value) {
        $nextApplication = array($env, $value);
        while (is_array($nextApplication)) {
            if ($value instanceof Scheme_Symbol) {
                return $env->get($value->name);
            } elseif ($value instanceof Scheme_Pair) {
                /*
                $nextApplication = $this->getApplication($env, $value->car, $value->cdr->listToArray());
                list($env, $value) = $nextApplication;
                */
                return $this->apply($env, $value->car, $value->cdr->listToArray());
            } elseif ($value instanceof Scheme_PhpCallback) {
                return $value->evaluate($nextApplication[2]);
            } else {
                return $value;
            }
        }
    }
    
    private function getApplication(Scheme_Env $env, Scheme_Form $value, array $args) {
        $value = $this->evaluate($env, $value);
        //FIXME special forms and tail recursion
        //FIXME application represenation uglyness
        if ($value instanceof Scheme_Lambda) {
            $args = $this->evaluateEach($env, $args);
            return $value->makeActivation($args);
        } elseif ($value instanceof Scheme_PhpCallback) {
            $args = $this->evaluateEach($env, $args);
            return array($env, $value, $args);
        } elseif ($value instanceof Scheme_SpecialForm) {
            return $value->makeActivation($execEnv, $args);
        } else {
            throw new Scheme_Error("Invalid application - " . get_class($value));
        }
    }
    
    private function apply(Scheme_Env $env, Scheme_Form $value, array $args) {
        $value = $this->evaluate($env, $value);
        if ($value instanceof Scheme_PhpCallback) {
            $args = $this->evaluateEach($env, $args);
            return $value->evaluate($args);
        } else {
            throw new Scheme_Error("Invalid application - " . get_class($value));
        }
    }
    
    private function evaluateEach(Scheme_Env $env, array $vals) {
        foreach ($vals as $key => $val) {
            $vals[$key] = $this->evaluate($env, $val);
        }
        return $vals;
    }
}