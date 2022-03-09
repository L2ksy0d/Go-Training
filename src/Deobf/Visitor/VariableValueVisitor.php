<?php
namespace Deobf\Visitor;

use Error;
use PhpParser\PrettyPrinter;
use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\NodeVisitorAbstract;

class VariableValueVisitor extends NodeVisitorAbstract
{
    static $result = [];
    static $variablename = '';

    public function __construct()
    {
        $this->prettyPrinter = new PrettyPrinter\Standard;
    }

    public function enterNode(Node $node) {
        /*
        用来将所有的变量传入全局表
        */
        
        
    }

    public function afterTraverse(array $nodes)
    {
        //file_put_contents("php://stdout", json_encode(self::$result,JSON_UNESCAPED_SLASHES). PHP_EOL, FILE_APPEND | LOCK_EX);
        self::$result = [];
    }
}
