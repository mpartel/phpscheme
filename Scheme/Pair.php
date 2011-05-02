<?php
class Scheme_Pair implements Scheme_Form {
    public $car;
    public $cdr;
    
    public function __construct(Scheme_Form $car, Scheme_Form $cdr) {
        $this->car = $car;
        $this->cdr = $cdr;
    }
}

