<?php
namespace Deobf\Visitor;

use Deobf\GlobalTable;
use Error;
use PhpParser\PrettyPrinter;
use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Scalar;
use PhpParser\Node\Stmt;
use PhpParser\NodeVisitorAbstract;

class MagicConstVisitor extends NodeVisitorAbstract
{
    /*
    该类为还原系统常量的类，主要还原以下常量
    __FILE__
    __LINE__
    __DIR__
    上面三个中FILE和DIR无论运行到哪里都可以直接获取，LINE可以用自带的方法获得
    下面几种初步考虑是创建作用域的数据结构存放这些信息，后来想到可以采用往父节点遍历的方式
    __NAMESPACE__
    __CLASS__
    __FUNCTION__
    __METHOD__
    __TRAIT__
    */
    public function __construct()
    {
        
    }

    public function leaveNode(Node $node) {
        /*
        用来还原所有的系统常量节点
        */
        if($node instanceof Scalar\MagicConst\Dir){
            $dir = __DIR__;
            return new Node\Scalar\String_("$dir");
        }elseif($node instanceof Scalar\MagicConst\Line){
            $line = $node->getAttribute('startLine');
            return new Node\Scalar\String_("$line");
        }elseif($node instanceof Scalar\MagicConst\File){
            $file = __FILE__;
            return new Node\Scalar\String_("$file");
        }elseif($node instanceof Scalar\MagicConst\Class_){
            $findtype = 'Stmt_Class';
            $class = $this->FindNode($node,$findtype);
            return new Node\Scalar\String_("$class");
        }elseif($node instanceof Scalar\MagicConst\Function_){
            $findtype = 'Stmt_ClassMethod';
            $function = $this->FindNode($node,$findtype);
            return new Node\Scalar\String_("$function");
        }elseif($node instanceof Scalar\MagicConst\Namespace_){
            $findtype = 'Stmt_Namespace';
            $namespace = $this->FindNode($node,$findtype);
            return new Node\Scalar\String_("$namespace");
        }elseif($node instanceof Scalar\MagicConst\Trait_){
            $findtype = 'Stmt_Trait';
            $trait = $this->FindNode($node,$findtype);
            return new Node\Scalar\String_("$trait");
        }elseif($node instanceof Scalar\MagicConst\Method){
            //Method返回需要带上类名，所以这里要分开遍历类名和方法名，然后进行拼接返回
            $findtype = 'Stmt_Class';
            $class = $this->FindNode($node,$findtype);
            $findtype = 'Stmt_ClassMethod';
            $method = $this->FindNode($node,$findtype);
            $ret = $class . '\\' . $method;
            return new Node\Scalar\String_("$ret");
        }
    }

    public function FindNode(Node $node,$findtype = null){
        $ret = '';
        $op_count = 50;
        while($op_count > 0){
            $parent = $node->getAttribute('parent');
            if($parent == NULL){
                break;
            }
            $type = $parent->getType();
            if($type == $findtype){
                /* 当节点类型为Namespace时，AST如下
                Stmt_Namespace(
                    name: Name(
                        parts: array(
                            0: test
                        )
                    )
                    stmts: array(
                所以这里要判断然后取name->parts[0]的值
                其他情况都是$node->name->name
                */
                if($findtype == "Stmt_Namespace"){
                    $ret =  $parent->name->parts[0];
                    break;
                }else{
                    $ret =  $parent->name->name;
                    break;
                }
            }
            $node = $parent;
            $op_count -= 1;
        }
        return $ret;
    }

    // public function afterTraverse(array $nodes)
    // {
    //         $this->globaldata->outputdata();
    // }
}
