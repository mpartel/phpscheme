<?php
class Scheme_Lambda implements Scheme_SpecialForm {
    private $defnEnv;
    private $argNames;
    private $body;
    
    public function __construct(Scheme_Env $defnEnv, array $argNames, Scheme_Value $body) {
        $this->defnEnv = $defnEnv;
        $this->argNames = $argNames;
        $this->body = $body;
    }
    
    public function evaluate(Scheme_Env $execEnv, array $args) {
        if (count($args) != count($this->argNames)) {
            throw new Scheme_Error("Expected " . count($this->argNames) . " but got " . count($args));
        }
        $argCount = count($this->argNames);

        $interp = $execEnv->getInterpreter();
        $bodyEnv = $this->defnEnv->createChildEnv();
        for ($i = 0; $i < $argCount; ++$i) {
            $value = $interp->evaluate($execEnv, $args[$i]);
            $bodyEnv->bind($this->argNames[$i], $value);
        }
        return new Scheme_TailCall($this->body, $bodyEnv);
    }
    
    public function toString() {
        return "<lambda>";
    }
}