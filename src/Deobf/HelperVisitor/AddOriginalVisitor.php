<?php
namespace Deobf\HelperVisitor;
use PhpParser\Node;
use PhpParser\PrettyPrinter;

class AddOriginalVisitor extends \PhpParser\NodeVisitorAbstract
{
    private $deobfusator;

    public function __construct()
    {
        $this->prettyPrinter = new PrettyPrinter\Standard;
    }

    public function enterNode(Node $node)
    {
        if (!($node instanceof Node\Scalar\EncapsedStringPart)) {
            $node->setAttribute('comments', array(new \PhpParser\Comment('/* ' . $this->prettyPrinter->prettyPrint(array($node), false) . ' */')));
        }
    }

}
