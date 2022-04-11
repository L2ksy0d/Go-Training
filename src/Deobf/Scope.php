<?php
namespace Deobf;
use PhpParser\Node;

class Scope{

    private $nameScope = array(
        'function' => '',
        'namespace' => '',
        'class' => '',
        'method' => '',
        'trait' => ''
    );
    private $parentScope;
    private $name;

    public function __construct($name, Scope $parent = null)
    {
        $this->name = $name;
        $this->parentScope = $parent;
    }

    public function getParent()
    {
        return $this->parentScope;
    }

    public function __toString()
    {
        return "{$this->parentScope}.{$this->name}";
    }

    public function updateNameScope(Node $node, $isEnter)
    {
        $key = null;
        if ($node instanceof Node\Stmt\Namespace_) {
            $key = 'namespace';
        } elseif ($node instanceof Node\Stmt\Function_) {
            $key = 'function';
        } elseif ($node instanceof Node\Stmt\Class_) {
            $key = 'class';
        } elseif ($node instanceof Node\Stmt\ClassMethod) {
            $key = 'method';
        } elseif ($node instanceof Node\Stmt\Trait_) {
            $key = 'trait';
        } else {
            return;
        }
        if ($isEnter) {
            $name = $node->name ? (is_string($node->name) ? $node->name : $node->name->toString()) : '';
            if ($key == 'method') {
                // function is set to the name of the method
                $this->nameScope['function'] = $name;
                $parentName = $this->nameScope['class'] . $this->nameScope['trait'];
                if ($parentName) {
                    $name = $parentName . '::' . $name;
                }
            }
            // If we've entered into a trait, the class can't be known
            if ($key == 'trait') {
                $this->nameScope['class'] = null;
            }
            if (in_array($key, array('class', 'trait', 'function')) && $this->nameScope['namespace']) {
                $name = $this->nameScope['namespace'] . '\\' . $name;
            }
            if ($key == 'function') {
                $this->nameScope['method'] = $name;
            }
            $this->nameScope[$key] = $name;
        } else {
            $this->nameScope[$key] = '';
            if ($key == 'method') {
                $this->nameScope['function'] = '';
            }
            if ($key == 'function') {
                $this->nameScope['method'] = '';
            }
            if ($key == 'trait') {
                $this->nameScope['class'] = '';
            }
        }
    }
}