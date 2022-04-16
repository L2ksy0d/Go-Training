<?php
namespace Deobf\Feature;

use PhpParser\PrettyPrinter;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use Deobf;

class GlobalVariableVisitor extends NodeVisitorAbstract
{
    static $result = [];
    static $globalname = '';
    static $count = [];

    public function __construct()
    {
        $this->prettyPrinter = new PrettyPrinter\Standard;
        $this->output = new Deobf\Output;
    }

    public function leaveNode(Node $node) {
            
        /*
        用来识别全局变量;
          如果要获取全局变量格式无需考虑value的节点类型
          expr: Expr_ArrayDimFetch(
                var: Expr_Variable(
                    name: _POST
                )
            )
        */
        if ($node instanceof Node\Expr\ArrayDimFetch && $node->var instanceof Node\Expr\Variable && (in_array($node->var->name ,GLOBAL_VAR)))
        {
            self::$globalname = $this->prettyPrinter->prettyPrintExpr($node);
            array_push(self::$result,self::$globalname);
        }

        /*
        用来获取普通变量、数组名、对应数组元素名
        */
    }

    public function afterTraverse(array $nodes)
    {
        // echo "Global Traverse success!\n";
        self::$count[] = count(array_unique(self::$result));
        $this->output->getcsvdata('GV',self::$count);
        self::$result = [];
        self::$count = [];
    }
}
