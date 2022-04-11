<?php

namespace DeObf\Traits;

use DeObf\GlobalConstant\Sources;
use DeObf\OtherVisitor\EvalVisitor;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node;

trait TypeHelper
{
    public function isString(Node $node){
        return $node instanceof String_ || $node instanceof Node\Scalar\EncapsedStringPart;
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
        return $node instanceof Name;
    }

    public function isIdentifier(Node $node)
    {
        return $node instanceof Identifier;
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
            if (!$item->value instanceof String_) {
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