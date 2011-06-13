<?php
class Scheme_Unspecified implements Scheme_Value {
    public function toString() {
        return '<unspecified>';
    }
}