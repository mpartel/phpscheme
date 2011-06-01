<?php
error_reporting(E_ALL | E_NOTICE);

set_include_path(
    dirname(dirname(__FILE__)) . '/lib' . PATH_SEPARATOR .
    dirname(__FILE__) . '/lib'
);

function myAutoload($name) {
    require_once str_replace('_', '/', $name) . '.php';
}

spl_autoload_register('myAutoload');

class TestRunner {
    private $successes = array();
    private $failures = array();
    
    public function runAllTests($testDir) {
        $dirHandle = dir($testDir);
        while (($file = $dirHandle->read()) !== false) {
            if (preg_match('/Test.php$/', $file)) {
                $this->runTestFile($testDir . '/' . $file);
            }
        }
        
        $totalTests = count($this->successes) + count($this->failures);
        
        echo "\n";
        echo "\n";
        if (empty($this->failures)) {
            echo "All $totalTests tests passed.\n";
        } else {
            foreach ($this->failures as $failure) {
                $this->printFailure($failure);
            }
            echo count($this->failures) . " of $totalTests tests failed.\n";
        }
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
                    $this->addFailure($testClass, $method, $e);
                }
                if (!isset($e)) {
                    $this->addSuccess($testClass, $method);
                } else {
                    unset($e);
                }
            }
        }
    }
    
    private function addSuccess($testClass, $method) {
        echo '.';
        $this->successes[] = array($testClass, $method);
    }
    
    private function addFailure($testClass, $method, Exception $exception) {
        echo 'F';
        $this->failures[] = array($testClass, $method, $exception);
    }
    
    private function printFailure($failure) {
        list($testClass, $method, $exception) = $failure;
        echo "$testClass::$method failed:\n" . $exception . "\n\n";
    }
}


$runner = new TestRunner();
$runner->runAllTests(dirname(__FILE__) . '/misc');
