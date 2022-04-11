<?php
namespace Deobf\Visitor;

use Deobf\GlobalTable;
use Deobf\HelperVisitor\VariableReducer;
use Error;
use PhpParser\PrettyPrinter;
use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\NodeVisitorAbstract;
use PhpParser\NodeTraverser;

class VariableValueVisitor extends NodeVisitorAbstract
{

    public function __construct()
    {
        $this->prettyPrinter = new PrettyPrinter\Standard;
        $this->globaldata = new GlobalTable;
    }

    public function leaveNode(Node $node) {
        /*
        用来将所有的变量传入全局表
        */
        if($node instanceof Node\Expr\Assign && $node->expr instanceof Node\Scalar){
            $name = $node->var->name;
            $value = $node->expr->value;
            $this->globaldata->setvariablevalue($name,$value);
        }

        /*
        传入自定义函数的节点
        */
        if($node instanceof Node\Stmt\Function_){
            $function_name = "function_" . $node->name->name;
            $this->globaldata->setvariablevalue($function_name,$node);
        }
    }

    public function afterTraverse(array $nodes)
    {
            //$this->globaldata->outputdata();
            
    }
}
