<?php

namespace Deobf\Feature;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use PhpParser\NodeVisitor\ParentConnectingVisitor;
use Deobf;

class BinaryopVisitor extends NodeVisitorAbstract
{
    static $longest = 0;
    static $result = [];
    static $flag = 0;
    static $status = 0;
    static $bitandcount = 0;
    static $bitorcount = 0;
    static $xorcount = 0;
    static $booland = 0;
    static $boolor = 0;
    static $doubleboolnot = 0;
    static $bitwisenot = 0;
    static $assignconcat = 0;
    static $assignbitor = 0;
    static $assignbitxor = 0;
    static $assignbitand = 0;
    static $title = array('Status','MaxConcat','&','|','XOR','&&','||','!!','~','.=','&=','|=','^=');

    public function __construct()
    {
        $this->output = new Deobf\Output;
    }

    public function leaveNode(Node $node)
    {
        if($node instanceof Node\Expr\BinaryOp\Concat){
            $num = 1;
            $binary_op_count = 50;
            while($binary_op_count > 0){
                $parent = $node->getAttribute('parent');
                    if($parent instanceof Node\Expr\BinaryOp\Concat){
                        $node = $parent;
                        $num += 1;
                    }
                    else{
                        break;
                    }
                    $binary_op_count -= 1;
                }
            if(self::$longest < $num){
                self::$longest = $num;
            }
            if($binary_op_count === 0){
                self::$flag = 1;
            }
        }

        $type = $node->getType();
            switch ($type){
                case "Expr_BinaryOp_BitwiseAnd":
                    self::$bitandcount += 1;
                    break;
                case "Expr_BinaryOp_BitwiseOr" :
                    self::$bitorcount += 1;
                    break;
                case "Expr_BinaryOp_BitwiseXor" :
                    self::$xorcount += 1;
                    break;
                case "Expr_BinaryOp_BooleanAnd" :
                    self::$booland += 1;
                    break;
                case "Expr_BinaryOp_BooleanOr" :
                    self::$boolor += 1;
                    break;
                case "Expr_BitwiseNot" :
                    self::$bitwisenot += 1;
                    break;
                case "Expr_BooleanNot" :
                    if($node->getAttribute('parent') instanceof Node\Expr\BooleanNot){
                        self::$doubleboolnot += 1;
                    }
                    break;
                case "Expr_AssignOp_Concat" :
                    self::$assignconcat += 1;
                    break;
                case "Expr_AssignOp_BitwiseAnd" :
                    self::$assignbitand += 1;
                    break;
                case "Expr_AssignOp_BitwiseXor" :
                    self::$assignbitxor += 1;
                    break;
                case "Expr_AssignOp_BitwiseOr" :
                    self::$assignbitor += 1;
                    break;
                default:
                    break;
            }
    }

    public function afterTraverse(array $nodes)
    {
        // echo "BinaryOP Traverse success!\n";
        if(self::$flag === 1){
            self::$result['message'] = '超过拼接遍历阈值';
            self::$result['lenth'] = 50;
        }else{
            self::$result['message'] = '正常遍历退出';
            self::$result['lenth'] = self::$longest;
        }
        self::$result['bitand'] = self::$bitandcount;
        self::$result['bitor'] = self::$bitorcount;
        self::$result['xorcount'] = self::$xorcount;
        self::$result['booland'] = self::$booland;
        self::$result['boolor'] = self::$boolor;
        self::$result['doubleboolnot'] = self::$doubleboolnot;
        self::$result['birwisenot'] = self::$bitwisenot;
        self::$result['assignconcat'] = self::$assignconcat;
        self::$result['assignand'] = self::$assignbitand;
        self::$result['assignor'] = self::$assignbitor;
        self::$result['assignxor'] = self::$assignbitxor;
        $this->output->getcsvdata('BO',self::$result);
        // $file = fopen('Binaryop.csv','a+');
        // fputcsv($file, self::$result);
        // fclose($file);
        self::$longest = 0;
        self::$result = [];
        self::$flag = 0;
        self::$bitandcount = 0;
        self::$bitorcount = 0;
        self::$xorcount = 0;
        self::$booland = 0;
        self::$boolor = 0;
        self::$doubleboolnot = 0;
        self::$bitwisenot = 0;
        self::$assignconcat = 0;
        self::$assignbitand = 0;
        self::$assignbitor = 0;
        self::$assignbitxor = 0;
    }
    
}
