<?php
class TestRunner_Scheme_AssertionLib extends Scheme_Lib_Abstract {
    public function assert_equals(array $args) {
        $this->requireExactly(2, $args);
        return new Scheme_TailCall(Scheme_Utils::mkList(
            new Scheme_Symbol('if'),
            Scheme_Utils::mkList(new Scheme_Symbol('not'),
                Scheme_Utils::mkList(new Scheme_Symbol('equal?'), $args[0], $args[1])
            ),
            Scheme_Utils::mkList(new Scheme_Symbol('error!'),
                new Scheme_String("Expected " . $args[1]->toString() . " to yield " . $args[0]->toString())
            )
        ));
    }
    
    public function error(array $args) {
        $this->requireExactly(1, $args);
        $this->requireString($args[0]);
        throw new TestAssertionFailed($args[0]->value);
    }
    
    protected function mapMethodName($methodName) {
        if ($methodName == 'error') {
            return 'error!';
        } else {
            return parent::mapMethodName($methodName);
        }
    }
    
    protected function getSpecialFormMethods() {
        return array('assert_equals');
    }
}