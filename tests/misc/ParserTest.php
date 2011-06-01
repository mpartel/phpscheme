<?php
class ParserTest extends TestCase {
    public function testConstants() {
        $this->assertParsesAsSame("1");
        $this->assertParsesAsSame("11");
        $this->assertParsesAsSame("x");
        $this->assertParsesAsSame("*");
    }
    
    public function testExpressions() {
        $this->assertParsesAsSame("(* (+ 1 9) (+ 5 2))");
    }
    
    private function assertParsesAsSame($s) {
        $this->assertParsesAs($s, $s);
    }
    
    private function assertParsesAs($given, $expected) {
        $parser = new Scheme_Parser();
        if ($parser->parse($given)->toString() != $expected) {
            $s = $parser->parse($given)->toString();
            throw new Exception("ERROR: $given   ->   $s   (expected $expected)\n");
        }
    }
}