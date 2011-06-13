<?php
abstract class Scheme_PhpLibrary {
    public function bindToEnv(Scheme_Env $env) {
        foreach ($this->getLibraryMethods() as $method) {
            $name = $this->mapMethodName($method);
            $env->bind($name, new Scheme_PhpCallback(array($this, $method), $name));
        }
        
        foreach ($this->getSpecialFormMethods() as $method) {
            $name = $this->mapMethodName($method);
            $env->bind($name, new Scheme_PhpSpecialFormCallback(array($this, $method), $name));
        }
    }
    
    protected function getLibraryMethods() {
        $result = array();
        $refl = new ReflectionClass($this);
        foreach ($refl->getMethods(ReflectionMethod::IS_PUBLIC) as $m) {
            $result[] = $m->getName();
        }
        $result = array_diff($result, array('bindToEnv'));
        return $result;
    }
    
    protected function getSpecialFormMethods() {
        return array();
    }
    
    protected function mapMethodName($methodName) {
        if (strpos($methodName, 'is') === 0) {
            return $this->lcfirst(substr($methodName, 2)) . '?';
        } else {
            return str_replace('_', '-', $methodName);
        }
    }
    
    private function lcfirst($s) {
        if (function_exists('lcfirst')) {
            return lcfirst($s);
        } else {
            return strtolower($s[0]) . substr($s, 1);
        }
    }
}