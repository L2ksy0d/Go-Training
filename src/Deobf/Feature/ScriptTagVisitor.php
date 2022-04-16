<?php
namespace Deobf\Feature;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use Deobf;

class ScriptTagVisitor extends NodeVisitorAbstract
{
    static $result = [];
    static $htmlvalue = '';
    static $match = [];
    static $count = [];
    static $output = [];

    public function __construct()
    {
        $this->out = new Deobf\Output;
    }

    public function enterNode(Node $node) {
        if ($node instanceof Node\Stmt\InlineHTML)
        {
            self::$result[] = str_replace(",","%2C",json_encode(utf8_encode($node->value),JSON_UNESCAPED_SLASHES));
        }
    }

    public function afterTraverse(array $nodes)
    {
        self::$htmlvalue = implode(self::$result);
        preg_match_all("/<[^>]+>/",self::$htmlvalue,self::$match);
        self::$count[] = count(self::$match[0]);
        $this->out->getcsvdata('ST',self::$count);    
        self::$result = [];
        self::$htmlvalue = '';
        self::$match = [];
        self::$count = [];
        self::$output = [];
    }
}
