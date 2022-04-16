<?php

namespace Deobf\Feature;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use Deobf;

class LongestVarVisitor extends NodeVisitorAbstract
{
    static $longest = 0;
    static $val = '';
    static $result = [];

    public function __construct()
    {
        $this->output = new Deobf\Output;
    }

    public function leaveNode(Node $node)
    {
        if($node instanceof Node\Scalar\String_){
            $value = $node->value;
            $length = strlen($value);
            if($length > self::$longest){
                self::$longest = $length;
                self::$val = str_replace(",","%2C",utf8_encode($value));
            }
        }
    }
    public function afterTraverse(array $nodes)
    {
        self::$result[] = self::$longest;
        //self::$result[] = json_encode(self::$val,JSON_UNESCAPED_SLASHES);
        $this->output->getcsvdata('LV',self::$result);
        // echo "Longest Traverse success!\n";
        // $file = fopen('LongestVar.csv','a+');
        // fputcsv($file, array(self::$longest, json_encode(self::$val,JSON_UNESCAPED_SLASHES)));
        // fclose($file);
        // $file = fopen('Strlenth.csv','a+');
        // fputcsv($file, self::$result);
        // fclose($file);
        self::$longest = 0;
        self::$val = '';
        self::$result = [];
    }
}