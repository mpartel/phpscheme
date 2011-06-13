<?php
class Scheme_Parser {
    public function parse($input) {
        $input = trim($input);
        $input = preg_replace("/[\t\n]+/", " ", $input);
        if (preg_match("/^[0-9]+$/", $input)) {
            return new Scheme_Int($input);
        }
        if (preg_match("/^[0-9]+\.[0-9]+$/", $input)) {
            return new Scheme_Float($input);
        }
        if (preg_match("/^\".*\"$/", $input)) {
            $center = substr($input, 1, -1);
            $escape = false;
            for ($i = 0; $i < strlen($center); $i++) {
                if ($escape) {
                    if ($center[$i] == "\\" || $center[$i] == "\"") {
                        $escape = false;
                    } else {
                        throw new Exception("Parse error");
                    }
                } else if ($center[$i] == "\"") {
                    throw new Exception("Parse error");
                } else if ($center[$i] == "\\") {
                    $escape = true;
                }
            }
            $center = stripslashes($center);
            return new Scheme_String($center);
        }
        if ($input == "#t" || $input == "#T") {
            return new Scheme_Bool(true);
        }
        if ($input == "#f" || $input == "#F") {
            return new Scheme_Bool(false);
        }
        if (preg_match("/^[-a-zA-Z?!+*\/_][-a-zA-Z0-9?!+*\/_]*$/", $input)) {
            return new Scheme_Symbol($input);
        }
        if ($input[0] == "'") {
            return new Scheme_Pair(new Scheme_Symbol("quote"), new Scheme_Pair($this->parse(substr($input, 1)), new Scheme_Null()));
        }
        if (preg_match("/^\(.*\)$/", $input)) {
            if (strlen($input) == 2) {
                return new Scheme_Null();
            }
            $center = trim(substr($input, 1, -1)) . " ";
            $parts = array();
            $level = 0;
            $part = "";
            $instring = false;
            $escape = false;
            for ($i = 0; $i < strlen($center); $i++) {
                if ($center[$i] == "(") $level++;
                if ($center[$i] == ")") $level--;
                if ($instring) {
                    if ($escape) {
                        if ($center[$i] == "\\" || $center[$i] == "\"") {
                            $escape = false;
                        } else {
                            throw new Exception("Parse error");
                        }                        
                    } else if ($center[$i] == "\"") {
                        $instring = false;
                    } else if ($center[$i] == "\\") {
                        $escape = true;
                    }
                } else if ($center[$i] == "\"") {
                    $instring = true;
                }
                if ($center[$i] == " " && $level == 0 && !$instring) {
                    if ($part <> "") $parts[] = $part;
                    $part = "";
                } else {
                    $part .= $center[$i];
                }
            }
            $result = new Scheme_Null();
            for ($i = count($parts) - 1; $i >= 0; $i--) {
                $result = new Scheme_Pair($this->parse($parts[$i]), $result);
            }
            return $result;
        }
        throw new Scheme_Error("Parse error");
    }
}
