<?php
namespace Deobf\HelperVisitor;

use Deobf\GlobalTable;
use Error;
use phpDocumentor\Reflection\PseudoTypes\False_;
use PhpParser\PrettyPrinter;
use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Scalar;
use PhpParser\Node\Stmt;
use PhpParser\NodeVisitorAbstract;
use PhpParser\NodeTraverser;

class VariableReducer extends NodeVisitorAbstract
{
    /*
    该类为还原变量的类，主要依赖于全局数据表进行数据替换
    */
    public function __construct()
    {
        $this->globaldata = new GlobalTable;
    }

    public function enterNode(Node $node)
    {
        if($node instanceof Node\Stmt\Function_ || $node instanceof Node\Stmt\Class_){
            return NodeTraverser::DONT_TRAVERSE_CHILDREN;
        }
    }

    public function leaveNode(Node $node) {
        /*
        判断当有变量时就进行数据查询
        */
        if($node instanceof Node\Expr\Variable){
                $name = $node->name;
                try {
                    $ret = $this->getValue($name);
                    $type = gettype($ret);
                    switch($type){
                        case 'integer':
                            return new Node\Scalar\DNumber($ret);
                            break;
                        case 'string':
                            return new Node\Scalar\String_($ret);
                            break;
                        default:
                            break;
                    }
                } catch (\Throwable $th) {
                    //throw $th;
                }
        }
        if($node instanceof Node\Expr\ArrayDimFetch && $node->var instanceof Node\Expr\Variable && $node->dim instanceof Node\Scalar){
            if($node->dim instanceof Node\Scalar\String_){
                $variablekey = $node->var->name."['".$node->dim->value."']";
            }elseif($node->dim instanceof Node\Scalar\LNumber){
                $variablekey = $node->var->name."[".$node->dim->value."]";
            }
            try {
                $ret = $this->getValue($variablekey);
                $type = gettype($ret);
                switch($type){
                    case 'integer':
                        return new Node\Scalar\DNumber($ret);
                        break;
                    case 'string':
                        return new Node\Scalar\String_($ret);
                        break;
                    default:
                        break;
                }
            } catch (\Throwable $th) {
                //throw $th;
            }
        }   
    }

    public function getValue($variable){
        return $this->globaldata->getvariablevalue($variable);
    }
}

    
