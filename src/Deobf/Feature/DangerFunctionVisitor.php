<?php
namespace Deobf\Feature;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use Deobf\Util\NodeUtil;
use Deobf;

class DangerFunctionVisitor extends NodeVisitorAbstract
{

    static $result = [];
    static $eval = 0;
    static $assert = 0;
    static $passthru = 0;
    static $exec = 0;
    static $system = 0;
    static $popen = 0;
    static $proc_popen = 0;
    static $shell_exec = 0;
    static $pcntl_exec = 0;
    static $mail = 0;
    static $iniset = 0;
    static $putenv = 0;
    static $apache_setenv = 0;
    static $mb_send_mail = 0;
    static $dl = 0;
    static $error_log = 0;
    static $mb_regex_set_options = 0;
    static $preg_filter = 0;
    static $create_functions = 0;
    static $filter_var = 0;
    static $filter_var_array = 0;
    static $create_function = 0;
    static $ReflectionFunction = 0;
    static $mediumfunc = [];
    static $lowfunc = [];
    static $otherfunc = [];
    static $fileopfunc = [];

    public function __construct()
    {
        $this->output = new Deobf\Output;
    }

    public function leaveNode(Node $node)
    {
        if ($node instanceof Node\Expr\FuncCall) {
            $fname_info = NodeUtil::getFunctionName($node);
            $fname_type = $fname_info['type'];
            $fname = $fname_info['name'];
            if ('str' !== $fname_type) {
                return;
            }
            // 如果函数是危险函数
            if (in_array(ltrim($fname, '\\'), HIGH_DANGER_FUNCTION, true)) {
                switch(ltrim($fname, '\\')){
                    case "assert":
                        self::$assert += 1;
                        break;
                    case "passthru":
                        self::$passthru += 1;
                        break;
                    case "exec":
                        self::$exec += 1;
                        break;
                    case "system":
                        self::$system += 1;
                        break;
                    case "popen":
                        self::$popen += 1;
                        break;
                    case "proc_popen":
                        self::$proc_popen += 1;
                        break;  
                    case "shell_exec":
                        self::$shell_exec += 1;
                        break;
                    case "pcntl_exec":
                        self::$pcntl_exec += 1;
                        break;
                    case "mail":
                        self::$mail += 1;
                        break;
                    case "putenv":
                        self::$putenv += 1;
                        break;
                    case "ini_set":
                        self::$iniset += 1;
                        break;
                    case "apache_setenv":
                        self::$apache_setenv += 1;
                        break;
                    case "mb_send_mail":
                        self::$mb_send_mail += 1;
                        break;
                    case "dl":
                        self::$dl += 1;
                        break;
                    case "error_log":
                        self::$error_log += 1;
                        break;
                    case "mb_regex_set_options":
                        self::$mb_regex_set_options += 1;
                        break;
                    case "preg_filter":
                        self::$preg_filter += 1;
                        break;
                    case "create_function":
                        self::$create_function += 1;
                        break;
                    case "create_functions":
                        self::$create_functions += 1;
                        break;
                    case "filter_var":
                        self::$filter_var += 1;
                        break;
                    case "filter_var_array":
                        self::$filter_var_array += 1;
                        break;
                    case "ReflectionFunction":
                        self::$ReflectionFunction += 1;
                        break; 
                    default:
                        break;          
                }
            }elseif(in_array(ltrim($fname, '\\'), MEDIUM_DANGER_FUNCTION, true)) {
                array_push(self::$mediumfunc, $fname);

            }elseif(in_array(ltrim($fname, '\\'), LOW_DANGER_FUNCTION,true)) {
                array_push(self::$lowfunc, $fname);

            }elseif(in_array(ltrim($fname, '\\'), FILE_OPERATION,true)) {
                array_push(self::$fileopfunc, $fname);

            }else{
                array_push(self::$otherfunc, $fname);

            }
        }

        if($node instanceof Node\Expr\Eval_){
            $fname = 'eval';
            self::$eval += 1;
        }
    }
    public function afterTraverse(array $nodes)
    {
                self::$result = array(count(self::$mediumfunc),count(self::$lowfunc),count(self::$fileopfunc),count(self::$otherfunc),self::$eval,self::$assert,self::$passthru,self::$exec,self::$system,self::$popen,self::$proc_popen,self::$shell_exec,self::$pcntl_exec,self::$mail,self::$iniset,self::$putenv,self::$apache_setenv,self::$mb_send_mail,self::$dl,self::$error_log,self::$mb_regex_set_options,self::$preg_filter,self::$create_functions,self::$filter_var,self::$filter_var_array,self::$create_function,self::$ReflectionFunction);

        $this->output->getcsvdata('DF',self::$result);
        self::$result = []; 
        self::$eval = 0;
        self::$assert = 0;
        self::$passthru = 0;
        self::$exec = 0;
        self::$system = 0;
        self::$popen = 0;
        self::$proc_popen = 0;
        self::$shell_exec = 0;
        self::$pcntl_exec = 0;
        self::$mail = 0;
        self::$iniset = 0;
        self::$putenv = 0;
        self::$apache_setenv = 0;
        self::$mb_send_mail = 0;
        self::$dl = 0;
        self::$error_log = 0;
        self::$mb_regex_set_options = 0;
        self::$preg_filter = 0;
        self::$create_functions = 0;
        self::$filter_var = 0;
        self::$filter_var_array = 0;
        self::$create_function = 0;
        self::$ReflectionFunction = 0;
        self::$mediumfunc = [];
        self::$lowfunc = [];
        self::$otherfunc = [];
        self::$fileopfunc = [];
    }
}
