<?php
abstract class TestRunner {
    /**
     * @var TestReporter
     */
    protected $reporter;
    
    public function __construct(TestReporter $reporter) {
        $this->reporter = $reporter;
    }
    
    public abstract function runAllTests($testDir);
}