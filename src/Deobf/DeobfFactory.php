<?php
namespace Deobf;

use Symfony\Component\Finder\Finder;
use Deobf\DeobfManager;

class DeobfFactory
{
    public function create(): DeobfManager
    {
        return $this->createFromDir(__DIR__ . '/Visitor');
    }

    public function createFromDir(string $dir): DeobfManager
    {
        $manager = new DeobfManager;
        $finder = new Finder();
        $finder->files()->in($dir)->name('*.php');
        foreach($finder as $file){
            $class_name = $file->getBasename('.php');
            $manager->addVisitor('Deobf\Visitor\\' . $class_name);
        }
        return $manager;
    }
}