<?php
class InterpreterTest extends TestCase {
    /**
     * @var Scheme_Env
     */
    private $rootEnv;
    
    /**
     * @var Scheme_InterpreterWithMetrics
     */
    private $interp;
    
    public function setUp()
    {
        $this->interp = new Scheme_InterpreterWithMetrics();
        $this->rootEnv = $this->interp->createEnv();
        
        $lib = new Scheme_Lib_Predef();
        $lib->bindToEnv($this->rootEnv);
    }
    
    
    public function testSimpleMathExprs() {
        $this->assertEvalsTo("(+ x 10)", "13", array('x' => new Scheme_Int(3)));
        $this->assertEvalsTo("(+ x x)", "6", array('x' => new Scheme_Int(3)));
        $this->assertEvalsTo("(+ (+ x x) (+ 2 2))", "10", array('x' => new Scheme_Int(3)));
    }
    
    public function testSimpleListExprs() {
        $this->assertEvalsTo("(car (cons x 7))", "3", array('x' => new Scheme_Int(3)));
        $this->assertEvalsTo("(cdr (cons x 7))", "7", array('x' => new Scheme_Int(3)));
    }
    
    public function testSimpleLambda() {
        $this->assertEvalsTo("((lambda (x) (+ x x)) 4)", "8");
    }
    
    public function testNestedLambda() {
        $this->assertEvalsTo("(((lambda (x) (lambda (x) (+ x x))) 10) 8)", "16");
    }
    
    
    public function testStackDepthMetric() {
        $this->evalCode("1");
        $this->assertEquals(1, $this->interp->maxStackDepth);
        
        $this->evalCode("(+ 3 7)");
        $this->assertEquals(2, $this->interp->maxStackDepth);
        
        $this->evalCode("(+ 1 (+ 2 (* 3 4)))");
        $this->assertEquals(4, $this->interp->maxStackDepth);
    }
    
    public function testTailCall() {
        $code = <<<CODE
(letrec
  ((last (lambda (xs)
          (if (null? (cdr xs)) (car xs) (last (cdr xs))))))
  (last '(1 2 3 4 5 6 7 8 9 10 11 12 13 14 15 16 17 18 19 20)))
CODE;
        $this->assertEvalsTo($code, "20");
        $this->assertTrue($this->interp->maxStackDepth < 5);
    }
    
    
    private function assertEvalsTo($code, $expected, array $envVars = array()) {
        $result = $this->evalCode($code, $envVars);
        $result = $result->toString();
        
        if ($result != $expected) {
            throw new Exception("$code   ->   $result   (expected $expected)\n");
        }
    }
    
    private function evalCode($code, array $envVars = array()) {
        $parser = new Scheme_Parser($code);
        $expr = $parser->parse($code);
        
        $env = $this->rootEnv->createChildEnv();
        $env->bindAll($envVars);
        
        return $this->interp->evaluate($env, $expr);
    }
}