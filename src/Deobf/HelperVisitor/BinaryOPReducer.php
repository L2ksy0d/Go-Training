<?php
namespace Deobf\HelperVisitor;

use Deobf\GlobalTable;
use Error;
use PhpParser\PrettyPrinter;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use PhpParser\NodeTraverser;
use TypeHelper;

class BinaryOPReducer extends NodeVisitorAbstract
{
    /*
    该类为还原变量的类，主要依赖于全局数据表进行数据替换
    */
    public function __construct()
    {
        $this->globaldata = new GlobalTable;
        $this->traverser = new NodeTraverser();
        $this->prettyPrinter = new PrettyPrinter\Standard;
    }

    public function leaveNode(Node $node) {
        /*
        判断当有变量时就进行数据查询
        */
        if($node instanceof Node\Expr\BinaryOp\Concat){
            $ret = $this->assignBinaryOpHandler($node, 'concat');

            if(is_string($ret)){
                return new Node\Scalar\String_($ret);
            }
            else{
                return;
            }
        }
        if($node instanceof Node\Expr\BinaryOp\BitwiseXor){
            $ret = $this->assignBinaryOpHandler($node, 'xor');
            if(is_string($ret)){
                return new Node\Scalar\String_($ret);
            }
            else{
                return;
            }
        }
        if($node instanceof Node\Expr\BinaryOp\BitwiseOr){
            $ret = $this->assignBinaryOpHandler($node, 'or');
            if(is_string($ret)){
                return new Node\Scalar\String_($ret);
            }
            else{
                return;
            }
        }
        if($node instanceof Node\Expr\BinaryOp\BitwiseAnd){
            $ret = $this->assignBinaryOpHandler($node, 'and');
            if(is_string($ret)){
                return new Node\Scalar\String_($ret);
            }
            else{
                return;
            }
        }
        if($node instanceof Node\Expr\BinaryOp\Greater){
            $ret = $this->assignBinaryOpHandler($node, 'greater');
            if(isset($ret) && !is_string($ret)){
                return new Node\Scalar\LNumber($ret);
            }
            else{
                return;
            }
        }
        if($node instanceof Node\Expr\BinaryOp\Plus){
            $ret = $this->assignBinaryOpHandler($node, 'plus');
            if(isset($ret) && !is_string($ret)){
                return new Node\Scalar\LNumber($ret);
            }
            else{
                return;
            }
        }

        if($node instanceof Node\Expr\BinaryOp\Div){
            $ret = $this->assignBinaryOpHandler($node, 'div');
            if(isset($ret) && !is_string($ret)){
                return new Node\Scalar\LNumber($ret);
            }
            else{
                return;
            }
        }
        
    }

    public function assignBinaryOp($left_val, $right_val, $op){
        if(!isset($left_val)){
            return;
        }else{
            switch($op){
                case "xor":
                    return $left_val ^ $right_val;
                case "concat":
                    return $left_val . $right_val;
                case "and":
                    return $left_val & $right_val;
                case "or":
                    return $left_val | $right_val;
                case "greater":
                    return $left_val > $right_val;
                case "less":
                    return $left_val < $right_val;
                case "plus":
                    return $left_val + $right_val;
                case "minus":
                    return $left_val - $right_val;
                case "mul":
                    return $left_val * $right_val;
                case "div":
                    return $left_val / $right_val;
                default:
                    break;
            }
        }
    }

    public function assignBinaryOpHandler($node, $op)
    {
        if($this->isValue($node->left) && $this->isValue($node->right)){
            return $this->assignBinaryOp($node->left->value, $node->right->value, $op);
        }
        if($this->isString($node->left) && $this->isString($node->right)) {
            return $this->assignBinaryOp($node->left->value, $node->right->value, $op);
        }
        if($this->isConcat($node->left)){
            return $this->assignBinaryOpHandler($node->left,'concat');
        }
        if($this->isVariable($node->left) && $this->isVariable($node->right)){
            //echo "left:".$node->left->name."\n";
            //echo "right:".$node->right->name."\n";
            if($this->isVariable($node->left)){
                $left_value = $this->globaldata->getvariablevalue($node->left->name);
            }
            if($this->isVariable($node->right)){
                $right_value = $this->globaldata->getvariablevalue($node->right->name);
            }
            return $this->assignBinaryOp($left_value,$right_value,$op);
        }
        if($this->isVariable($node->left) && $this->isString($node->right)){
            $left_value = $this->globaldata->getvariablevalue($node->left->name);
            $right_value = $node->right->value;
            return $this->assignBinaryOp($left_value, $right_value, $op);
        }
        if($this->isVariable($node->right) && $this->isString($node->left)){
            //echo "debug";
            $right_value = $this->globaldata->getvariablevalue($node->right->name);
            $left_value = $node->left->value;
            return $this->assignBinaryOp($left_value, $right_value, $op);
        }
        if($this->isFuncCall($node->left)){
            echo "left";
            $expr = $this->prettyPrinter->prettyPrintExpr($node->left);
            $pattan = '/\$[_a-zA-Z][A-Za-z0-9_]*/';
            $result = '';
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
            eval("\$result = $expr;");
            $node->left =  new Node\Scalar\String_("$result");
            return $this->assignBinaryOpHandler($node, $op);
        }
        elseif($this->isFuncCall($node->right)){
            echo "right";
            $expr = $this->prettyPrinter->prettyPrintExpr($node->right);
            $pattan = '/\$[_a-zA-Z][A-Za-z0-9_]*/';
            $result = '';
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
            eval("\$result = $expr;");
            $node->right =  new Node\Scalar\String_("$result");
            return $this->assignBinaryOpHandler($node, $op);
        }
        else{
            return;
        }
    }












    public function isString(Node $node){
        return $node instanceof Node\Scalar\String_ || $node instanceof Node\Scalar\EncapsedStringPart;
    }

    public function isEncapsedParts(Node $node){
        return $node instanceof Node\Scalar\Encapsed;
    }

    public function isQualify(Node $node)
    {
        return $this->isName($node) || $this->isIdentifier($node);
    }

    public function isName(Node $node)
    {
        return $node instanceof Node\Name;
    }

    public function isIdentifier(Node $node)
    {
        return $node instanceof Node\Identifier;
    }

    public function isInlineHTML(Node $node)
    {
        return $node instanceof Node\Stmt\InlineHTML;
    }

    public function isEval(Node $node)
    {
        return $node instanceof Node\Expr\Include_ || $node instanceof Node\Expr\Eval_;
    }

    public function isShellExec(Node $node)
    {
        return $node instanceof Node\Expr\ShellExec;
    }

    public function isEcho(Node $node)
    {
        return $node instanceof Node\Stmt\Echo_;
    }

    public function isPrint(Node $node)
    {
        return $node instanceof Node\Expr\Print_;
    }

    public function isErrorSuppress(Node $node)
    {
        return $node instanceof Node\Expr\ErrorSuppress;
    }

    public function isClosure(Node $node)
    {
        return $node instanceof Node\Expr\Closure;
    }

    public function isNumber(Node $node)
    {
        return $node instanceof Node\Scalar\LNumber || $node instanceof Node\Scalar\DNumber;
    }

    public function isConstant(Node $node)
    {
        return $node instanceof Node\Expr\ConstFetch;
    }

    public function isFunctionEnter(Node $node)
    {
        return $node instanceof Node\Stmt\Function_ || $node instanceof Node\Stmt\ClassMethod;
    }

    public function isClassMethod(Node $node)
    {
        return $node instanceof Node\Stmt\ClassMethod || $node instanceof Node\Expr\StaticCall;
    }

    public function isVariable(Node $node)
    {
        if($node instanceof Node\Expr\PropertyFetch || $node instanceof Node\Expr\Variable){
            return true;
        }
        return false;
    }

    public function isValue(Node $node)
    {
        if($node instanceof Node\Scalar\String_ || $node instanceof Node\Scalar\DNumber
            || $node instanceof Node\Scalar\LNumber){
            return true;
        }
        return false;
    }

    public function isExpression(Node $node)
    {
        return $node instanceof Node\Stmt\Expression;
    }

    public function isArray(Node $node)
    {
        return $node instanceof Node\Expr\Array_;
    }

    public function isPureArray(Node $node)
    {
        if (!$node instanceof Node\Expr\Array_) {
            return false;
        }

        foreach ($node->items as $item) {
            if (!$item->value instanceof Node\Scalar\String_) {
                return false;
            }
        }
        return true;
    }

    public function isArrayDimFetch(Node $node)
    {
        return $node instanceof Node\Expr\ArrayDimFetch;
    }

    public function isConcat(Node $node)
    {
        return $node instanceof Node\Expr\BinaryOp\Concat || $node instanceof Node\Expr\AssignOp\Concat;
    }

    public function isBinaryOp(Node $node)
    {
        return $node instanceof Node\Expr\BinaryOp;
    }

    public function isFuncCall(Node $node)
    {
        return $node instanceof Node\Expr\FuncCall;
    }

    public function isFunction_(Node $node)
    {
        return $node instanceof Node\Stmt\Function_;
    }
    public function isMethodCall(Node $node)
    {
        return $node instanceof Node\Expr\MethodCall;
    }

    public function isArrayCallback(Node $node)
    {
        return $node instanceof Node\Name\FullyQualified;
    }

    public function isJumpStmt(Node $node)
    {
        if ($node instanceof Node\Stmt\If_ || $node instanceof Node\Stmt\Switch_
            || $node instanceof Node\Stmt\TryCatch || $node instanceof Node\Expr\Ternary
            || $node instanceof Node\Expr\BinaryOp\LogicalOr) {
            return true;
        }
        return false;
    }

    public function isLoopStmt(Node $node)
    {
        if ($node instanceof Node\Stmt\For_ || $node instanceof Node\Stmt\Foreach_
            || $node instanceof Node\Stmt\While_ || $node instanceof Node\Stmt\Do_) {
            return true;
        }
        return false;
    }

    public function isStmtFor(Node $node)
    {
        return $node instanceof Node\Stmt\For_;
    }

    public function isStmtForeach(Node $node){
        return $node instanceof Node\Stmt\Foreach_;
    }

    public function isStmtWhile(Node $node){
        return $node instanceof Node\Stmt\While_;
    }

    public function isStmtDo(Node $node){
        return $node instanceof Node\Stmt\Do_;
    }

    public function isReturnStmt(Node $node)
    {
        if ($node instanceof Node\Stmt\Return_)
            return true;
        return false;
    }

    public function isStopStmt(Node $node)
    {
        if($node instanceof Node\Stmt\Throw_ || $node instanceof Node\Stmt\Break_ || $node instanceof Node\Stmt\Continue_){
            return true;
        }
        return false;
    }

    public function isCallStmt(Node $node)
    {
        if($node instanceof Node\Expr\FuncCall){
            return true;
        }
        return false;
    }

    public function isAssignExpr(Node $node)
    {
        if($node instanceof Node\Expr\Assign){
            return true;
        }
        return false;
    }

    public function isAssignOp(Node $node)
    {
        if($node instanceof Node\Expr\AssignOp){
            return true;
        }
        return false;
    }
    public function isStmtProperty(Node $node){
        return $node instanceof Node\Stmt\Property;
    }


    /**
     * @param  Node\Expr\FuncCall $node
     * @return array
     * @throws NodeTypeException
     */
    public function getFunctionName(Node\Expr\FuncCall $node)
    {
        $fname_info = [];
        if ($this->isName($node->name)) {
            $fname_info['name'] = $node->name->toLowerString();
            $fname_info['type'] = 'str';
            return $fname_info;
        } elseif ($this->isString($node->name)) {
            $fname_info['name'] = strtolower($node->name->value);
            $fname_info['type'] = 'str';
            return $fname_info;
        } elseif($this->isVariable($node->name)) {
            $fname_info['name'] = $node->name->name;
            $fname_info['type'] = 'var';
            return $fname_info;
        }elseif($this->isArrayDimFetch($node->name)){
            $fname_info['name'] = $node->name->var->name;
            $fname_info['type'] = 'arr';
            return $fname_info;
        }else{
            print_r("unknown node");
        }
    }

    /**
     * @param  Node\Expr\MethodCall | Node\Expr\StaticCall $node
     * @return Node|string
     * @throws NodeTypeException
     */
    public function getMethodName(Node $node)
    {
        if ($this->isIdentifier($node->name)) {
            return $node->name->toLowerString();
        } else {
            throw new NodeTypeException();
        }
    }
}
