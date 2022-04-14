<?php
namespace Deobf\HelperVisitor;

use Deobf\GlobalTable;
use Error;
use PhpParser\PrettyPrinter;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\NodeDumper;

class FuncallReducer extends NodeVisitorAbstract
{
    /*
    该类为还原变量的类，主要依赖于全局数据表进行数据替换
    */
    public function __construct()
    {
        $this->globaldata = new GlobalTable;
        $this->prettyPrinter = new PrettyPrinter\Standard;
        $this->traverser = new NodeTraverser();
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
        if($node instanceof Node\Expr\FuncCall && $node->name instanceof Node\Expr\Variable){
            $funcname = $node->name->name;
            $arg = $node->args;
            if($this->globaldata->intable($funcname)){
                $value = $this->globaldata->getvariablevalue($funcname);
                return new Node\Expr\FuncCall(new Node\Scalar\String_("$value"),$arg);
            }
        }
        if($node instanceof Node\Expr\FuncCall && $node->name instanceof Node\Name && $this->globaldata->intable("function_".$node->name->parts[0])){
            $args = [];
            $this->traverser->addVisitor(new VariableReducer);
            $this->traverser->traverse(array($node));
            foreach($node->args as $value){
                $args[] = $value->value->value;
            }
            $ori_node = $this->globaldata->getvariablevalue("function_".$node->name->parts[0]);
            $ori_node_copy = $ori_node;
            $ori_code = $this->prettyPrinter->prettyPrintFile($ori_node_copy->stmts);
            //echo $ori_code."\n\n";
            $i = 0;
            try {
                foreach($ori_node_copy->params as $value){
                    $value->var->name = "$" . $value->var->name;
                    //进行替换
                    $ori_code = str_replace($value->var->name,$args[$i],$ori_code);
                    //删除php标签
                    $ori_code = str_replace("<?php","",$ori_code);
                    $ori_code = preg_replace("/(\r\n|\r|\t)/i", '', $ori_code);
                    $i += 1;
                }
                return new Node\Scalar\String_("$ori_code");
            } catch (\Throwable $th) {
                //throw $th;
            }
            
        }

        /*
        处理使用数组值作为函数时
        */
        if($node instanceof Node\Expr\FuncCall && $node->name instanceof Node\Expr\ArrayDimFetch){
            $args = $node->args;
            if($node->name->var instanceof Node\Expr\Variable){
                $variable = $node->name->var->name;
            }
            if($node->name->dim instanceof Node\Scalar\String_){
                $variablekey = "'".$node->name->dim->value."'";
            }elseif($node->name->dim instanceof Node\Scalar\LNumber){
                $variablekey = $node->name->dim->value;
            }
            $value = $this->globaldata->getvariablevalue($variable . '[' . $variablekey . ']');
            return new Node\Expr\FuncCall(new Node\Name($value),$args);
        }

        /*
        处理一般情况下的函数调用
        */
        if($node instanceof Node\Expr\FuncCall && $node->name instanceof Node\Name && !($this->globaldata->intable("function_".$node->name->parts[0]))){
            $name = $node->name->parts[0];
            $args = [];
            $this->traverser->addVisitor(new VariableReducer);
            $this->traverser->traverse(array($node));
            foreach($node->args as $key){
                if($key->value instanceof Node\Scalar){
                    $args[] = $key->value->value;
                }elseif($key->value instanceof Node\Expr\Variable){
                    $args[] = $key->value->name;
                }
            }
            try{
                $ret = call_user_func_array($name, $args);
                if(is_string($ret)){
                    return new Node\Scalar\String_($ret);
                }
                //return new Node\Expr\Assign($node->var,new Node\Scalar\String_("$result"));
            }catch(Error $e){
                print("Error! Occurs in the FuncallReducer.php file");
                echo $e->getMessage();
            }
        }
    }
}
