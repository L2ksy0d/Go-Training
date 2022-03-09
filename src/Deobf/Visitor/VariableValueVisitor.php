<?php
namespace Deobf\Visitor;

use Deobf\GlobalTable;
use Error;
use PhpParser\PrettyPrinter;
use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\NodeVisitorAbstract;

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
        if($node instanceof Node\Expr\Assign){
            $name = $node->var->name;
            $value = $node->expr->value;
            $this->globaldata->setvariablevalue($name,$value);
            $this->globaldata->outputdata();
        }
        
    }
}
