<?php
namespace Deobf\Util;

use PhpParser\Node\Name\FullyQualified;

class NodeUtil
{
    public static function getFunctionName($node)
    {
        //var_dump($node);
        $fname_info = [];
        if ($node->name instanceof FullyQualified || $node->name instanceof \PhpParser\Node\Name) {
            $fname_info['name'] = $node->name->toLowerString();
            $fname_info['type'] = 'str';
            return $fname_info;
        } elseif ($node->name instanceof \PhpParser\Node\Scalar\String_) {
            $fname_info['name'] = strtolower($node->name->value);
            $fname_info['type'] = 'str';
            return $fname_info;
        } elseif($node->name instanceof \PhpParser\Node\Expr\Variable) {
            $fname_info['name'] = $node->name->name;
            $fname_info['type'] = 'var';
            return $fname_info;
        }elseif($node->name instanceof \PhpParser\Node\Expr\ArrayDimFetch){
            $fname_info['name'] = $node->name->var->name;
            $fname_info['type'] = 'arr';
            return $fname_info;
        }elseif($node->name instanceof \PhpParser\Node\Name){
            echo "aaaa";
            // $fname_info['name'] = $node->name->var->name;
            // $fname_info['type'] = 'arr';
            // return $fname_info;
        }else{
            //print_r("unknown node");
        }
    }
}