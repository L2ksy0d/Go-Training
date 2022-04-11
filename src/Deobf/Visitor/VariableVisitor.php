<?php
namespace Deobf\Visitor;

use Error;
use PhpParser\PrettyPrinter;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use Deobf\GlobalTable;
use Deobf\HelperVisitor\BinaryOPReducer;
use Deobf\HelperVisitor\VariableReducer;
use PhpParser\NodeTraverser;

class VariableVisitor extends NodeVisitorAbstract
{
    static $result = [];
    static $variablename = '';

    public function __construct()
    {
        $this->prettyPrinter = new PrettyPrinter\Standard;
        $this->globaldata = new GlobalTable;
        $this->traverser = new NodeTraverser();
    }

    public function enterNode(Node $node) {
        /*
        用来将初始的函数声明还原
        */
        if($node instanceof Node\Expr\AssignOp){
            $op = str_replace('AssignOp', 'BinaryOp', get_class($node));
            return new Node\Expr\Assign($node->var, new $op($node->var, $node->expr));
        }

        if($node instanceof Node\Expr\Assign){
            if($node->expr instanceof Node\Scalar){
                $name = $node->var->name;
                $value = $node->expr->value;
                $this->globaldata->setvariablevalue($name,$value);
            }elseif($node->expr instanceof Node\Stmt){
                
            }elseif($node->expr instanceof Node\Expr\FuncCall && $node->name instanceof Node\Name){
                $name = $node->var->name;
                $expr = $this->prettyPrinter->prettyPrintExpr($node->expr);
                $result = "";
                $pattan = '/\$[_a-zA-Z][A-Za-z0-9_]*/';
                preg_match_all($pattan, $expr, $pat_array);
                if(!empty($pat_array[0])){
                    foreach($pat_array[0] as $value){
                        if($this->globaldata->intable($value)){
                            $ret = $this->globaldata->getvariablevalue(substr($value,1));
                        $ret = "'".addcslashes($ret,"'")."'";
                        if($ret){
                            $expr = str_replace($value,$ret,$expr);
                        }
                        } 
                    }
                }
                try{
                    eval("\$result = $expr;");
                    $this->globaldata->setvariablevalue($name,$result);
                    return new Node\Expr\Assign($node->var,new Node\Scalar\String_("$result"));
                }catch(Error $e){
                    print("Error! Occurs in the variableVisitor.php file");
                    echo $e->getMessage();
                }
            }elseif($node->expr instanceof Node\Expr\BinaryOp){
                $this->traverser->addVisitor(new BinaryOPReducer);
                $this->traverser->traverse(array($node->expr));
                $name = $node->var->name;
                $value = $node->expr->value;
                $this->globaldata->setvariablevalue($name,$value);
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
