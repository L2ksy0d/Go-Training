<?php
namespace Deobf;

use Error;
use PhpParser\PrettyPrinter;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use Deobf\GlobalTable;
use Deobf\HelperVisitor\VariableReducer;

class PreVisitor extends NodeVisitorAbstract
{
    public function leaveNode(Node $node) {
        /*
        用来将初始的函数声明还原
        */
        if ($node instanceof Node\Expr\AssignOp) {
            $op = str_replace('AssignOp', 'BinaryOp', get_class($node));
            return new Node\Expr\Assign($node->var, new $op($node->var, $node->expr));
        }
    }

    public function afterTraverse(array $nodes)
    {
        
    }
}
