<?php

use Deobf\DeobfFactory;
use Symfony\Component\Finder\Finder;

require 'vendor/autoload.php';

ini_set('xdebug.max_nesting_level', 100000000000);
ini_set('memory_limit', '-1');

$finder = new Finder();
$cmd_arr = getopt('p:');
$original = 0;
if(empty(getopt('p:'))){
    die("Enter the scan path after -p\n"); 
}else{
    if(is_dir($cmd_arr['p']) && file_exists($cmd_arr['p'])){
    $dir = $cmd_arr['p'];
    $flag = 0;
    }elseif(is_file($cmd_arr['p'])){
        $flag = 1;
        $filedir = substr($cmd_arr['p'],0,strripos($cmd_arr['p'], '/'));
        $filename = trim(strrchr($cmd_arr['p'], '/'),'/');
        if(!file_exists($filedir)){
            die("The path $cmd_arr[p] does not exist\n");
        }
    }
}
$orig = in_array('-o',$argv);
if($orig){
    $original = 1; 
}else{
    $original = 0;
}

if($flag){
    $finder->files()->in($filedir)->name($filename);
}else{
    $finder->files()->in($dir)->name('*.php');
}

foreach($finder as $file){
    $maxlenth = 0;
    $maxline = 0;
    $file_name = $file->getPathname();
    $file_contents = file_get_contents($file);
    $php_script_pattern = '/<script language=\"php\">([\s\S]*?)<\/script>+/i';
    try {
        $factory = (new DeobfFactory)->create();
        //脚本标签
        $code = '<?php ';
        preg_match_all($php_script_pattern, $file_contents,$res_array);
        $code = $code . implode(PHP_EOL, $res_array[1]);
        if($code === '<?php '){
            $code = $file_contents;
        }
        $alarm = $factory->detect($file_name, $code,$original);
    } catch (PhpParser\Error $e) {
        //echo "aa";
    }

}