<?php

namespace Deobf\Feature;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use Deobf;
use Deobf\Deobf as DeobfDeobf;

class ConditionStmtVisitor extends NodeVisitorAbstract
{
     static  $stmt_count = 0;
     static  $stmt_break = 0;
     static  $stmt_catch = 0;
     static  $stmt_continue = 0;
     static  $stmt_do = 0;
     static  $stmt_else = 0;
     static  $stmt_elseif = 0;
     //static  $stmt_finally = 0;
     static  $stmt_for = 0;
     static  $stmt_foreach = 0;
     static  $stmt_goto = 0;
     static  $stmt_if = 0;
     //static  $stmt_nop = 0;
     static  $stmt_return = 0;
     static  $stmt_switch = 0;
     static  $stmt_trycatch = 0;
     static  $stmt_while = 0;
     static  $expr_ternary = 0;
     static  $result = [];
     static  $title = array('Stmt_Count','Break','Catch','Continue','Do','Else','ElseIf','For','Foreach','Goto','If','Return','Switch','TryCatch','While','Ternary');
     static  $flag = 0;


    public function __construct()
    {
        $this->output = new Deobf\Output;
    }

    public function leaveNode(Node $node)
    {
         if($node instanceof Node\Stmt){
             self::$stmt_count += 1;
             $type = $node->getType();
             switch ($type){
                 case "Stmt_Break":
                     self::$stmt_break += 1;
                     break;
                 case "Stmt_Catch":
                     self::$stmt_catch += 1;
                     break;
                 case "Stmt_Continue":
                     self::$stmt_continue += 1;
                     break;
                 case "Stmt_Do":
                     self::$stmt_do += 1;
                     break;
                 case "Stmt_Else":
                     self::$stmt_else += 1;
                     break;
                 case "Stmt_ElseIf":
                     self::$stmt_elseif += 1;
                     break;
                 case "Stmt_For":
                     self::$stmt_for += 1;
                     break;
                 case "Stmt_Foreach":
                     self::$stmt_foreach += 1;
                     break;
                 case "Stmt_Goto":
                     self::$stmt_goto += 1;
                     break;
                 case "Stmt_If":
                     self::$stmt_if += 1;
                     break;
                 case "Stmt_Return":
                     self::$stmt_return += 1;
                     break;
                 case "Stmt_Switch":
                     self::$stmt_switch += 1;
                     break;
                 case "Stmt_TryCatch":
                     self::$stmt_trycatch += 1;
                     break;
                 case "Stmt_While":
                     self::$stmt_while += 1;
                     break;
                 default:
                     break;
             }
         }

         if ($node instanceof \PhpParser\Node\Expr\Ternary){
             self::$stmt_count += 1;
             self::$expr_ternary += 1;
         }
    }

    public function afterTraverse(array $nodes)
    {
        // echo "ConditionStmt Traverse success!\n";
        self::$result = array(self::$stmt_count,self::$stmt_break,self::$stmt_catch,self::$stmt_continue,self::$stmt_do,self::$stmt_else,self::$stmt_elseif,self::$stmt_for,self::$stmt_foreach,self::$stmt_goto,self::$stmt_if,self::$stmt_return,self::$stmt_switch,self::$stmt_trycatch,self::$stmt_while,self::$expr_ternary);
        $this->output->getcsvdata('CD',self::$result);
        // $file = fopen('Condition.csv','a+');
        // fputcsv($file, self::$result);
        // fclose($file);
        self::$result = [];
        self::$stmt_count = 0;
        self::$stmt_break = 0;
        self::$stmt_catch = 0;
        self::$stmt_continue = 0;
        self::$stmt_do = 0;
        self::$stmt_else = 0;
        self::$stmt_elseif = 0;
        //self::$stmt_finally = 0;
        self::$stmt_for = 0;
        self::$stmt_foreach = 0;
        self::$stmt_goto = 0;
        self::$stmt_if = 0;
        //self::$stmt_nop = 0;
        self::$stmt_return = 0;
        self::$stmt_switch = 0;
        self::$stmt_trycatch = 0;
        self::$stmt_while = 0;
        self::$expr_ternary = 0;
    }
}
