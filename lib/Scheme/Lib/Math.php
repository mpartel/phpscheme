<?php
class Scheme_Lib_Math extends Scheme_Lib_Base {
    
    public function plus(array $args) {
        $this->requireAtLeast(2, $args);
        $this->requireInt($args);
        //$sum = "";
        $sum = 0;
        foreach ($args as $arg) {
            //$sum = bcadd($sum, $arg->value);
            $sum = (int)$sum + (int)$arg->value;
        }
        return new Scheme_Int($sum);
    }
    
    protected function mapMethodName($method) {
        $map = array(
            'plus' => '+'
        );
        return isset($map[$method]) ? $map[$method] : parent::mapMethodName($method);
    }
}