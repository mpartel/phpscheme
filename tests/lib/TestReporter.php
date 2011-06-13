<?php
class TestReporter {
    private $successes = array();
    private $failures = array();
    
    public function printSummary() {
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
    
    public function addSuccess($testClass, $method) {
        echo '.';
        $this->successes[] = array($testClass, $method);
    }
    
    public function addFailure($testClass, $method, Exception $exception) {
        echo 'F';
        $this->failures[] = array($testClass, $method, $exception);
    }
    
    protected function printFailure($failure) {
        list($testClass, $method, $exception) = $failure;
        echo "$testClass::$method failed:\n" . $exception . "\n\n";
    }
}