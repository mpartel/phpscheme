<?php
class Scheme_Utils {
    public static function mkList(/* ... */) {
        return self::arrayToList(func_get_args());
    }
    
    public static function arrayToList(array $schemeVals) {
        $list = new Scheme_Null();
        foreach (array_reverse($schemeVals) as $val) {
            assert('$val instanceof Scheme_Value');
            $list = new Scheme_Pair($val, $list);
        }
        return $list;
    }
}