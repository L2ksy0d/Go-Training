<?php
namespace Deobf\Feature;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\ParserFactory;
use PhpParser\NodeTraverser;
use PhpParser\PrettyPrinter\Standard;
use PhpParser\NodeVisitor\ParentConnectingVisitor;

class feature{
    protected $parser = null;
    protected $traverser;

    public function __construct()
    {
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
        $this->traverser->addVisitor(new ParentConnectingVisitor);
        $this->traverser->addVisitor(new BinaryopVisitor);
        $this->traverser->addVisitor(new ConditionStmtVisitor);
        $this->traverser->addVisitor(new ConfuseFunctionVisitor);
        $this->traverser->addVisitor(new DangerFunctionVisitor);
        $this->traverser->addVisitor(new LongestVarVisitor);
        $this->traverser->addVisitor(new ScriptTagVisitor);
        $this->traverser->addVisitor(new GlobalVariableVisitor);
        $this->traverser->addVisitor(new VariableNameVisitor);
    }

    public function extract_feature($code){
        $stmts = $this->parser->parse($code);
        $stmts = $this->traverser->traverse($stmts);
    }
}