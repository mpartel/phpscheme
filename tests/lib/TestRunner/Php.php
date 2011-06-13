<?php
class TestRunner_Php extends TestRunner {
    
    public function runAllTests($testDir) {
        $dirHandle = dir($testDir);
        while (($file = $dirHandle->read()) !== false) {
            if (preg_match('/Test.php$/', $file)) {
                $this->runTestFile($testDir . '/' . $file);
            }
        }
        $dirHandle->close();
    }
    
    private function runTestFile($file) {
        require_once $file;
        $testClass = basename($file, '.php');
        $this->runTestClass($testClass);
    }
    
    private function runTestClass($testClass) {
        $refl = new ReflectionClass($testClass);
        $haveSetUp = $refl->hasMethod('setUp');
        $methods = $refl->getMethods(ReflectionMethod::IS_PUBLIC);
        foreach ($methods as $method) {
            $method = $method->getName();
            if (preg_match('/^test/', $method)) {
                $testObj = new $testClass;
                try {
                    if ($haveSetUp) {
                        $testObj->setUp();
                    }
                    $testObj->$method();
                } catch (Exception $e) {
                    $this->reporter->addFailure($testClass, $method, $e);
                }
                if (!isset($e)) {
                    $this->reporter->addSuccess($testClass, $method);
                } else {
                    unset($e);
                }
            }
        }
    }
}