<?php
class Scheme_Pair implements Scheme_Value, Scheme_ListVal {
    public $car;
    public $cdr;
    
    public function __construct(Scheme_Value $car, Scheme_Value $cdr) {
        $this->car = $car;
        $this->cdr = $cdr;
    }
    
    public function listToArray() {
        $result = array();
        $p = $this;
        while (true) {
            if ($p instanceof Scheme_Null) {
                return $result;
            } elseif ($p instanceof Scheme_Pair) {
                $result[] =  $p->car;
                $p = $p->cdr;
            } else {
                throw new Scheme_Error("Expected a list but didn't end in null");
            }
        }
    }

    public function toString() {
        $parts = $this->listToArray();
        $result = "";
        foreach ($parts as $part) {
            if ($result <> "") $result .= " ";
            $result .= $part->toString();
        }
        return "(" . $result . ")";
    }
}
