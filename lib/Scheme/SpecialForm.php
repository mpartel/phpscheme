<?php
interface Scheme_SpecialForm extends Scheme_Value {
    public function makeActivationEnv(Scheme_Env $execEnv, array $args);
}