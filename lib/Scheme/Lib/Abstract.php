<?php
abstract class Scheme_Lib_Abstract extends Scheme_PhpLibrary {
    
    protected function requireExactly($count, array $args) {
        if (count($args) != $count) {
            throw new Scheme_Error("Expected exactly $count arguments but got " . count($args));
        }
    }
    
    protected function requireAtLeast($count, array $args) {
        if (count($args) < $count) {
            throw new Scheme_Error("Expected at least $count arguments but got " . count($args));
        }
    }
    
    protected function requireList(Scheme_Value $arg) {
        if (!($arg instanceof Scheme_ListVal)) {
            throw new Scheme_Error("Expected a empty list but got " . $arg->toString());
        }
    }
    
    protected function requireNonemptyList(Scheme_Value $arg) {
        if (!($arg instanceof Scheme_Pair)) {
            throw new Scheme_Error("Expected a non-empty list but got " . $arg->toString());
        }
    }
    
    protected function requireSymbol(Scheme_Value $arg) {
        if (!($arg instanceof Scheme_Symbol)) {
            throw new Scheme_Error("Expected a symbol but got " . $arg->toString());
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
    
    protected function isTruthy(Scheme_Value $val) {
        return ($val instanceof Scheme_Bool && $val->value == true);
    }
    
}