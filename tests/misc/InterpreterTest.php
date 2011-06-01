<?php
class InterpreterTest {
    /**
     * @var Scheme_Env
     */
    private $rootEnv;
    
    /**
     * @var Scheme_Interpreter
     */
    private $interp;
    
    public function setUp()
    {
        $this->rootEnv = new Scheme_Env();
        
        $lib = new Scheme_Lib_Math();
        $lib->bindToEnv($this->rootEnv);
        
        $this->interp = new Scheme_Interpreter();
    }
    
    
    public function testMathExprs() {
        $this->assertEvalsTo("(+ x 10)", "13", array('x' => new Scheme_Int(3)));
        $this->assertEvalsTo("(+ x x)", "6", array('x' => new Scheme_Int(3)));
        $this->assertEvalsTo("(+ (+ x x) (+ 2 2))", "10", array('x' => new Scheme_Int(3)));
    }
    
    private function assertEvalsTo($code, $expected, array $envVars = array()) {
        
        $parser = new Scheme_Parser($code);
        $expr = $parser->parse($code);
        
        $env = new Scheme_Env($this->rootEnv);
        $env->bindAll($envVars);
        
        $result = $this->interp->evaluate($env, $expr);
        $result = $result->toString();
        
        if ($result != $expected) {
            throw new Exception("$code   ->   $result   (expected $expected)\n");
        }
    }
}