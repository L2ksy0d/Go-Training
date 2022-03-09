<?php
namespace tests;
require_once __DIR__.'/../vendor/autoload.php';
define("ROOT_PATH", dirname(__DIR__)."/");

use DeObf\GraphVisitor\SinkVisitor;
use DeObf\Util\NodeUtil;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PHPUnit\Framework\TestCase;

class ObfTest extends TestCase{

    public function testGetFunctionName(){
        $obj = new NodeUtil;
        $node1 = new FuncCall(new Node\Name(new Node\Expr\Variable("test")));
        // $node2 = new FuncCall(new FuncCal)
        $this->assertEquals("test", $obj->getFuncNodeName($node1));
    }

    public function Log(){
        $log = new Logger('Tester');
        $log->pushHandler(new StreamHandler(ROOT_PATH . 'storage/logs/app.log', Logger::WARNING));
        $log->error("Error");
        return $log;
    }
}
