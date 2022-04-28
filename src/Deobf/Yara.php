<?php
namespace Deobf;

class Yara{
    
    protected $status;
    protected $yarapath;
    protected $filepath;

    public function __construct(array $yara)
    {
        if(empty($yara['yarapath'])){
            $this->yarapath = "./yarafile";
        }else{
            $this->yarapath = $yara['yarapath'];
        }
        $this->filepath = $yara['filepath'];
    }

    public function command(){
        //echo "yarapath: ".$this->yarapath." filepath: ".$this->filepath."\n";
        $cmd = "yara -C $this->yarapath $this->filepath";
        //echo $cmd."\n";
        $result = shell_exec($cmd);
        //echo $result;
        $this->check($result);
    }

    public function check($str){
        echo "----------------------------------------------------\n";
        echo "               \033[35mYara Rule Detect Result\033[0m              \n";
        echo "----------------------------------------------------\n";
        if(strpos($str,'Webshell') !== false){
            echo "\033[31mWebshell File\033[0m";
        }else{
            echo "\033[32mNormal File\033[0m";
        }
    }
}