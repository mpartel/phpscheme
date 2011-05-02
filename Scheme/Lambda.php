<?php
class Scheme_Lambda implements Scheme_SpecialForm {
    public $env;
    public $body;
    private $argNames;
    
    public function __construct(Scheme_Env $env, Scheme_Value $argList, Scheme_Form $body) {
        $this->env = $env;
        $this->body = $body;
        $this->argNames = $argList->listToString();
    }
    
    public function makeActivation(Scheme_Env $execEnv, array $args) {
        //TODO: variable number of args
        if (count($args) != count($this->argNames)) {
            throw new Scheme_Error("Expected " . count($this->argNames) . " but got " . count($args));
        }
        $argCount = count($this->argNames);

        $actEnv = new Scheme_Env($this->env);
        for ($i = 0; $i < $argCount; ++$i) {
            $actEnv->bind($this->argNames[$i], $args[$i]);
        }
        return array($actEnv, $this->body);
    }
    
    public function toString() {
        return "<lambda>";
    }
}