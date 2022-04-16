<?php
namespace Deobf\Feature;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use Deobf\Util\NodeUtil;
use Deobf;

class ConfuseFunctionVisitor extends NodeVisitorAbstract
{

    static $result = [];

    public function __construct()
    {
        $this->output = new Deobf\Output;
    }

    public function leaveNode(Node $node)
    {
        if ($node instanceof Node\Expr\FuncCall) {
            $fname_info = NodeUtil::getFunctionName($node);
            $fname_type = $fname_info['type'];
            $fname = $fname_info['name'];
            if ('str' !== $fname_type) {
                return;
            }

            // 如果函数是混淆函数
            if (in_array(ltrim($fname, '\\'), CONFUSE_FUNCTION, true)) {
                array_push(self::$result, $fname);
            }
        }
    }
    public function afterTraverse(array $nodes)
    {
        // echo "ConfuseFunction Traverse success!\n";
        // $file = fopen('ConfuseFunction.csv','a+');
        // fputcsv($file, self::$result);
        // fclose($file);
        $this->output->getcsvdata('CF',count(self::$result));
        self::$result = [];
    }
}