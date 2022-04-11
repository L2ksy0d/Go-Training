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
            $dumper = new NodeDumper;
            //echo $dumper->dump($node) . "\n";
            // $count = count($node->args);
            foreach($node->args as $value){
                $args[] = $value->value->value;
            }
            $ori_node = $this->globaldata->getvariablevalue("function_".$node->name->parts[0]);
            $ori_node_copy = $ori_node;
            $ori_code = $this->prettyPrinter->prettyPrintFile($ori_node_copy->stmts);
            //echo $ori_code."\n\n";
            $i = 0;
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
        }
    }
}
