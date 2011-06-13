<?php
class Scheme_Lib_Math extends Scheme_Lib_Abstract {
    
    public function plus(array $args) {
        $this->requireAtLeast(2, $args);
        $this->requireInt($args);
        //$prod = "";
        $prod = 0;
        foreach ($args as $arg) {
            //$prod = bcadd($prod, $arg->value);
            $prod = (int)$prod + (int)$arg->value;
        }
        return new Scheme_Int($prod);
    }
    
    public function times(array $args) {
        $this->requireAtLeast(2, $args);
        $this->requireInt($args);
        //$prod = "";
        $prod = 0;
        foreach ($args as $arg) {
            //$prod = bcadd($prod, $arg->value);
            $prod = (int)$prod * (int)$arg->value;
        }
        return new Scheme_Int($prod);
    }
    
    protected function mapMethodName($method) {
        $map = array(
            'plus' => '+',
            'times' => '*'
        );
        return isset($map[$method]) ? $map[$method] : parent::mapMethodName($method);
    }
}