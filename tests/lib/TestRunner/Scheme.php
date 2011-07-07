<?php
class TestRunner_Scheme extends TestRunner_Php {
    public function runAllTests($testDir) {
        $dirHandle = dir($testDir);
        while (($file = $dirHandle->read()) !== false) {
            if (preg_match('/.test.ss$/', $file)) {
                $this->runTestFile($testDir . '/' . $file);
            }
        }
        $dirHandle->close();
    }
    
    private function runTestFile($file) {
        $code = file_get_contents($file);
        $testName = basename($file, '.test.ss');
        
        $parser = new Scheme_Parser();
        $interpreter = new Scheme_Interpreter();
        $rootEnv = $interpreter->createEnv();
        $this->bindLibs($rootEnv);
        
        try {
            $stmts = $parser->parseToplevelStatements($code);
            foreach ($stmts as $stmt) {
                $interpreter->evaluate($rootEnv, $stmt);
            }
        } catch (Exception $ex) {
            $this->reporter->addFailure('TestRunner_Scheme', $testName, $ex);
        }
        
        if (!isset($ex)) {
            $this->reporter->addSuccess('TestRunner_Scheme', $testName);
        }
    }
    
    private function bindLibs(Scheme_Env $env) {
        $this->bindLib($env, 'Scheme_Lib_Predef');
        $this->bindLib($env, 'TestRunner_Scheme_AssertionLib');
    }
    
    private function bindLib(Scheme_Env $env, $libName) {
        $lib = new $libName;
        $lib->bindToEnv($env);
    }
}