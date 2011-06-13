<?php
interface Scheme_SpecialForm extends Scheme_Value {
    public function evaluate(Scheme_Env $env, array $args);
}