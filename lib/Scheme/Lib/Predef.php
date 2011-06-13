<?php
class Scheme_Lib_Predef {
    public function bindToEnv(Scheme_Env $env) {
        $libs = array('Base', 'Bool', 'Math', 'List');
        foreach ($libs as $lib) {
            $class = "Scheme_Lib_$lib";
            $lib = new $class;
            $lib->bindToEnv($env);
        }
    }
}