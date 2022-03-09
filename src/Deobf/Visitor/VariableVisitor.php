<?php
namespace Deobf\Visitor;

use Error;
use PhpParser\PrettyPrinter;
use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\NodeVisitorAbstract;

class VariableVisitor extends NodeVisitorAbstract
{
    static $result = [];
    static $variablename = '';

    public function __construct()
    {
        $this->prettyPrinter = new PrettyPrinter\Standard;
    }

    public function enterNode(Node $node) {
        /*
        用来将初始的函数声明还原
        */
        if($node instanceof Node\Expr\Assign){
            if($node->expr instanceof Node\Scalar){
                
            }elseif($node->expr instanceof Node\Stmt){
                
            }elseif($node->expr instanceof Node\Expr\FuncCall){
                $expr = $this->prettyPrinter->prettyPrintExpr($node->expr);
                $result = "";
                try{
                    eval("\$result = $expr;");
                    return new Node\Expr\Assign($node->var,new Node\Scalar\String_("$result"));
                }catch(Error $e){
                    print("Error! Occurs in the variableVisitor.php file");
                    echo $e->getMessage();
                }
            } 
            //echo $result;
        }
        
    }

    public function afterTraverse(array $nodes)
    {
        //file_put_contents("php://stdout", json_encode(self::$result,JSON_UNESCAPED_SLASHES). PHP_EOL, FILE_APPEND | LOCK_EX);
        self::$result = [];
    }
}
