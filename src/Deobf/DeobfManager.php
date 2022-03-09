<?php
namespace Deobf;

use Symfony\Component\Finder\Finder;

class DeobfManager
{
    protected $visitor_classes = [];

    public function addVisitor(string $visitor){
        if(!in_array($visitor, $this->visitor_classes)){
            $this->visitor_classes[] = $visitor;
        }
    }

    public function detect(string $file_name, string $code){
        $result = array();
        $feature_ex= new Deobf($this->visitor_classes, $result);
        return $feature_ex->feed($file_name, $code);;
    }
}