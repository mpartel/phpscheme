<?php
abstract class Scheme_Lib_Base extends Scheme_PhpLibrary {
    
    protected function requireAtLeast($count, array $args) {
        if (count($args) < $count) {
            throw new Scheme_Error("At least $count arguments expected but got " . count($args));
        }
    }
    
    /**
     * @param array|Scheme_Value $vals
     */
    protected function requireInt($vals) {
        foreach ((array)$vals as $val) {
            if (!($val instanceof Scheme_Int)) {
                $valStr = $val->toString();
                throw new Scheme_Error("Integer required but '$valStr' given");
            }
        }
    }
}