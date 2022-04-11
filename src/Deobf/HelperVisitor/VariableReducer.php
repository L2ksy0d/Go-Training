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

class VariableReducer extends NodeVisitorAbstract
{
    /*
    该类为还原变量的类，主要依赖于全局数据表进行数据替换
    */
    public function __construct()
    {
        $this->globaldata = new GlobalTable;
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
    }

    public function getValue($variable){
        return $this->globaldata->getvariablevalue($variable);
    }
}

    
