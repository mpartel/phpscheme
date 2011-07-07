<?php
class ParserTest extends TestCase {
    public function testConstants() {
        $this->assertParsesAsSame("1");
        $this->assertParsesAsSame("11");
        $this->assertParsesAsSame("x");
        $this->assertParsesAsSame("*");
    }
    
    public function testSpecialCharsInConstants() {
        $this->assertParsesAsSame("one-two-three");
        $this->assertParsesAsSame("is-that-so?");
    }
    
    public function testExpressions() {
        $this->assertParsesAsSame("(* (+ 1 9) (+ 5 2))");
    }
    
    public function testQuote() {
        $this->assertParsesAs("'(1 2 3)", "(quote (1 2 3))");
    }
    
    public function testParsingToplevelStatements() {
        $parser = new Scheme_Parser();
        $result = $parser->parseToplevelStatements('(+ 1 2) (+ 3 4)');
        $this->assertTrue(is_array($result));
        $this->assertEquals(2, count($result));
        $this->assertEquals('(+ 1 2)', $result[0]->toString());
        $this->assertEquals('(+ 3 4)', $result[1]->toString());
    }
    
    private function assertParsesAsSame($s) {
        $this->assertParsesAs($s, $s);
    }
    
    private function assertParsesAs($given, $expected) {
        $parser = new Scheme_Parser();
        if ($parser->parse($given)->toString() != $expected) {
            $s = $parser->parse($given)->toString();
            throw new Exception("ERROR: `$given`   ->   `$s`   (expected `$expected`)\n");
        }
    }
}