<?php
class Scheme_Lib_Math extends Scheme_Lib_Abstract {
    
    public function plus(array $args) {
        $this->requireAtLeast(2, $args);
        $this->requireInt($args);
        $sum = "0";
        foreach ($args as $arg) {
            $sum = bcadd($sum, $arg->value);
        }
        return new Scheme_Int($sum);
    }
    
    public function minus(array $args) {
        $this->requireAtLeast(2, $args);
        $this->requireInt($args);
        $diff = $args[0]->value;
        array_shift($args);
        foreach ($args as $arg) {
            $diff = bcsub($diff, $arg->value);
        }
        return new Scheme_Int($diff);
    }
    
    public function times(array $args) {
        $this->requireAtLeast(2, $args);
        $this->requireInt($args);
        $prod = "1";
        foreach ($args as $arg) {
            $prod = bcmul($prod, $arg->value);
        }
        return new Scheme_Int($prod);
    }
    
    
    public function isZero(array $args) {
        $this->requireExactly(1, $args);
        $a = $args[0];
        $isZero = ($a instanceof Scheme_Int || $a instanceof Scheme_Float) && bccomp($a->value, "0") === 0;
        return new Scheme_Bool($isZero);
    }
    
    protected function mapMethodName($method) {
        $map = array(
            'plus' => '+',
            'minus' => '-',
            'times' => '*'
        );
        return isset($map[$method]) ? $map[$method] : parent::mapMethodName($method);
    }
}