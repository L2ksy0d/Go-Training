<?php
namespace Deobf\Feature;

use PhpParser\PrettyPrinter;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use Deobf;

class VariableNameVisitor extends NodeVisitorAbstract
{
    static $result = [];
    static $variablename = '';
    static $count = [];

    public function __construct()
    {
        $this->prettyPrinter = new PrettyPrinter\Standard;
        $this->output = new Deobf\Output;
    }

    public function leaveNode(Node $node) {
        /*
        用来获取普通变量、数组名、对应数组元素名
        */
        if ($node instanceof Node\Expr\Assign && $node->var instanceof Node\Expr\Variable && is_string($node->var->name)) {
            self::$variablename = $node->var->name;
            if(!in_array(self::$variablename, self::$result)){           
                array_push(self::$result, self::$variablename);
            }
        }
        elseif ($node instanceof Node\Expr\ArrayDimFetch && $node->var instanceof Node\Expr\ArrayDimFetch && $node->dim instanceof Node\Scalar) {
            self::$variablename = json_encode($this->prettyPrinter->prettyPrintExpr($node),JSON_UNESCAPED_SLASHES);
            if(!in_array(self::$variablename, self::$result)){           
                array_push(self::$result, self::$variablename);
            }
        }
        elseif($node instanceof Node\Expr\ArrayDimFetch && $node->var instanceof Node\Expr\Variable && (!in_array($node->var->name ,GLOBAL_VAR)) && $node->dim instanceof Node\Scalar\String_){
            self::$variablename = json_encode($this->prettyPrinter->prettyPrintExpr($node),JSON_UNESCAPED_SLASHES);
            if(!in_array(self::$variablename, self::$result)){           
                array_push(self::$result, self::$variablename);
            }
        }
        elseif($node instanceof Node\Expr\ArrayDimFetch && $node->var instanceof Node\Expr\Variable && (!in_array($node->var->name ,GLOBAL_VAR)) && $node->dim instanceof Node\Expr\ConstFetch){
            self::$variablename = json_encode($this->prettyPrinter->prettyPrintExpr($node),JSON_UNESCAPED_SLASHES);
            if(!in_array(self::$variablename, self::$result)){           
                array_push(self::$result, self::$variablename);
            }
        }
        elseif($node instanceof Node\Stmt\PropertyProperty && $node->name instanceof Node\VarLikeIdentifier){
            self::$variablename = $node->name->name;
            if(!in_array(self::$variablename, self::$result)){           
                array_push(self::$result, self::$variablename);
            }
        }
        elseif($node instanceof Node\Expr\Assign && $node->var instanceof Node\Expr\Variable && $node->var->name instanceof Node\Expr\FuncCall){
            self::$variablename = json_encode($this->prettyPrinter->prettyPrintExpr($node->var),JSON_UNESCAPED_SLASHES);
            self::$variablename = str_replace(",","%2C",self::$variablename);
            if(!in_array(self::$variablename, self::$result)){           
                array_push(self::$result, self::$variablename);
            }
        }
    }

    public function afterTraverse(array $nodes)
    {
        // echo "Variablename Traverse success!\n";
        self::$count[] = count(self::$result); 
        $this->output->getcsvdata('VN',self::$count);
        // $file = fopen('Variablename.csv','a+');
        // fputcsv($file, self::$result);
        // fclose($file);
        self::$result = [];
        self::$count = [];
    }
}
