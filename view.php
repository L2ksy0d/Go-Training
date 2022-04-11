<?php

require 'vendor/autoload.php';
use PhpParser\Error;
use PhpParser\NodeDumper;
use PhpParser\ParserFactory;

$code = file_get_contents('sample.php');
$parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
try {
    $ast = $parser->parse($code);
} catch (Error $error) {
    echo "Parse error: {$error->getMessage()}\n";
    return;
}

$dumper = new NodeDumper;
echo $dumper->dump($ast) . "\n";