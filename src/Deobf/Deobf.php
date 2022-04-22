<?php
namespace Deobf;


use Deobf\HelperVisitor\AddOriginalVisitor;
use Deobf\HelperVisitor\BinaryOPReducer;
use Deobf\HelperVisitor\FuncallReducer;
use Deobf\HelperVisitor\ControlFlowVisitor;
use DI\ContainerBuilder;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\ParserFactory;
use PhpParser\NodeTraverser;
use PhpParser\PrettyPrinter\Standard;
use PhpParser\NodeVisitor\ParentConnectingVisitor;

class Deobf
{
    protected $parser = null;
    protected $container;
    protected $traverser;
    protected $vistiors;
    protected $original;
    protected $add;

    public function __construct(array $visitors,$result,$original)
    {
        $this->original = $original;
        $this->bootstrapContainer();
        $this->bootstrapParser();
            array_unshift($visitors, \PhpParser\NodeVisitor\NameResolver::class);
        $this->bootstrapVisitor($visitors);
        $this->vistiors = $visitors;
    }

    protected function bootstrapParser(){
        $lexer = new \PhpParser\Lexer([
            'usedAttributes' => [
                'startLine',
                'endLine',
                'startFilePos',
                'endFilePos',
            ],
        ]);
        $this->parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7, $lexer);
        $this->traverser = new NodeTraverser();
        $this->prettyPrinter = new Standard;
    }

    protected function bootstrapContainer(){
        $builder = new ContainerBuilder();
        $this->container = $builder->build();
    }

    protected function bootstrapVisitor(array $visitors){
        try{
            if(True){
                // $this->traverser->addVisitor(new BinaryopVisitor);
                // $this->traverser->addVisitor(new ConditionStmtVisitor);
                //$this->traverser->addVisitor(new ConfuseFunctionVisitor);
                // $this->traverser->addVisitor(new DangerFunctionVisitor);
                // $this->traverser->addVisitor(new LongestVarVisitor);
                // $this->traverser->addVisitor(new ScriptTagVisitor);
                // $this->traverser->addVisitor(new GlobalVariableVisitor);
                // $this->traverser->addVisitor(new VariableNameVisitor);
            }
            $this->traverser->addVisitor(new PreVisitor);
            foreach($visitors as $visitor_name){
                $class = $this->container->get($visitor_name);
                $this->traverser->addVisitor($class);
            }
            if($this->original){
                $this->traverser->addVisitor(new AddOriginalVisitor);
            }
            
            $this->traverser->addVisitor(new NameResolver);
            $this->traverser->addVisitor(new ParentConnectingVisitor);
            //$this->traverser->addVisitor(new VariableReducer); 
            $this->traverser->addVisitor(new BinaryOPReducer);
            $this->traverser->addVisitor(new FuncallReducer); 
            //$this->traverser->addVisitor(new ControlFlowVisitor);  
        }catch(\DI\DependencyException $e){
            print_r('----------');
            print_r($e->getMessage());
        } catch(\DI\NotFoundException $e){
            print_r('----------');
            print_r($e->getMessage());
        }
    }

    // 对代码进行扫描
    // 处理逻辑
    public function feed($file_name, $code){
        try{
            $stmts = $this->parser->parse($code);
            $stmts = $this->traverser->traverse($stmts);
            $new_code = $this->prettyPrinter->prettyPrintFile($stmts);
            echo "----------------------------------------------------\n";
            echo "            PHP  Code  Deobfucate  Result           \n";
            echo "----------------------------------------------------\n";
            echo "Original  PHP  Code:|\n";
            echo "--------------------\n";
            echo $code.PHP_EOL;
            echo "----------------------------------------------------\n";
            echo "After Deobfucate PHP Code:|\n";
            echo "--------------------------\n";
            echo $new_code.PHP_EOL;
        }catch (\PhpParser\Error $e) {
            //print_r($e);
        }
        return $this;
    }

}