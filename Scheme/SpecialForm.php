<?php
interface Scheme_SpecialForm extends Scheme_Form {
    public function makeActivationEnv(Scheme_Env $execEnv, array $args);
}