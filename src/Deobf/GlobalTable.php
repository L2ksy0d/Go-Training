<?php
namespace Deobf;

class GlobalTable{ 
    static $variablevalue = [];

    public function getvariablevalue($name)
    {
        return self::$variablevalue[$name];
    }

    public function setvariablevalue($name,$value)
    {
        self::$variablevalue[$name] = $value;
    }

    public function outputdata()
    {
        var_dump(self::$variablevalue);
    }

    public function intable($name){
        return isset(self::$variablevalue[$name]);
    }
}