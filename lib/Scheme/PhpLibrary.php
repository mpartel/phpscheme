<?php
abstract class Scheme_PhpLibrary {
    public function bindToEnv(Scheme_Env $env) {
        foreach ($this->getLibraryMethods() as $method) {
            $name = $this->mapMethodName($method);
            $env->bind($name, new Scheme_PhpCallback(array($this, $method)));
        }
        
        foreach ($this->getSpecialFormMethods() as $method) {
            $name = $this->mapMethodName($method);
            $env->bind($name, new Scheme_PhpSpecialFormCallback(array($this, $method)));
        }
    }
    
    protected function getLibraryMethods() {
        $result = array();
        $refl = new ReflectionClass($this);
        foreach ($refl->getMethods(ReflectionMethod::IS_PUBLIC) as $m) {
            $result[] = $m->getName();
        }
        return $result;
    }
    
    protected function getSpecialFormMethods() {
        return array();
    }
    
    protected function mapMethodName($methodName) {
        return $methodName;
    }
}