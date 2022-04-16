<?php
namespace Deobf;

class Output
{
    static $output = [];
    static $VN = '';
    static $GV = '';
    static $ST = '';
    static $LV = '';
    static $BO = '';
    static $CF = '';
    static $CD = '';
    static $DF = '';
    static $ET = 0.0;
    static $CC = 0.0;
    static $len = 0;
    static $line = 0;
    static $NAME = '';
    static $total = '';
    static $flag_vn = 0;
    static $flag_gv = 0;
    static $flag_st = 0;
    static $flag_lv = 0;
    static $flag_bo = 0;
    static $flag_cf = 0;
    static $flag_cd = 0;
    static $flag_df = 0;
    static $flag_cc = 0;
    static $flag_et = 0;
    static $flag_len = 0;
    static $flag_line = 0;
    static $flag_name = 0;
    static $status = 0;
    static $title = array('name','Variable','global_var','number_script_tag','confuse_func','longest_var','length_text','max_length_line','info_entropy','index_coincidence','mid_func','low_func','file_op_func','other_func','eval','assert','passthru','exec','system','popen','proc_open','shell_exec','pcntl_exec','mail','ini_set','putenv','apache_setenv','mb_send_mail','dl','error_log','mb_regex_set_options','preg_filter','create_functions','filter_var','filter_var_array','create_function','ReflectionFunction','Status','Binary_concat','Binary_and','Binary_or','xor','nand','nor','not','contrast','Assign_concat','Assign_and','Assign_or','Assign_xor','Stmt_Count','Break','Catch','Continue','Do','Else','ElseIf','For','Foreach','Goto','If','Return','Switch','TryCatch','While','Ternary');


    
    public function __construct(){
    }

    public function getcsvdata($type,$data)
    {
        switch($type){
            case "VN":
                self::$VN = implode(",",$data);
                self::$flag_vn = 1;
                break;
            case "GV":
                self::$GV = implode(",",$data);
                self::$flag_gv = 1;
                break; 
            case "ST":
                self::$ST = implode(",",$data);
                self::$flag_st = 1;
                break;
            case "LV":
                self::$LV = implode(",",$data);
                self::$flag_lv = 1;
                break;
            case "BO":
                self::$BO = implode(",",$data);
                self::$flag_bo = 1;
                break;  
            case "CF": 
                self::$CF = $data;
                self::$flag_cf = 1;
                break;
            case "NAME":
                self::$NAME = implode(",",$data);
                self::$flag_name = 1;
                break;
            case "CD":
                self::$CD = implode(",",$data);
                self::$flag_cd = 1;
                break;
            case "DF":
                self::$DF = implode(",",$data);
                self::$flag_df = 1;
                break;
            case "ET":
                self::$ET = $data;
                self::$flag_et = 1;
                break;
            case "CC":
                self::$CC = $data;
                self::$flag_cc = 1;
                break;
            case "LT":
                self::$len = $data;
                self::$flag_len = 1;
                break;
            case "MAX":
                self::$line = $data;
                self::$flag_line = 1;
                break;   
            default:
                break;     
        }

        if(self::$flag_vn && self::$flag_gv && self::$flag_st && self::$flag_lv && self::$flag_bo && self::$flag_cf && self::$flag_cd && self::$flag_df && self::$flag_cc && self::$flag_et && self::$flag_name && self::$flag_len && self::$flag_line){
            self::$total = self::$NAME.",".self::$VN.",".self::$GV.",".self::$ST.",".self::$CF.",".self::$LV.",".self::$len.",".self::$line.",".self::$ET.",".self::$CC.",".self::$DF.",".self::$BO.",".self::$CD;
            self::$output = explode(",",self::$total);
            $this->output();
        }
    }

    public function output(){
        self::$output = array_combine(self::$title,self::$output);
        unset(self::$output['Status']);
        //var_dump(self::$output);
	    //file_put_contents("php://stdout", json_encode(self::$output,JSON_UNESCAPED_SLASHES). PHP_EOL, FILE_APPEND | LOCK_EX);
        echo "----------------------------------------------------\n";
        echo "               Feature Extract Result               \n";
        echo "----------------------------------------------------\n";
        echo json_encode(self::$output,JSON_UNESCAPED_SLASHES)."\n";
        echo "\n";
        self::$output = [];
        self::$total = '';
        self::$VN = '';
        self::$GV = '';
        self::$ST = '';
        self::$LV = '';
        self::$BO = '';
        self::$CF = '';
        self::$CD = '';
        self::$DF = '';
        self::$ET = 0.0;
        self::$CC = 0.0;
        self::$line = 0;
        self::$len = 0;
        self::$NAME = '';
        self::$flag_vn = 0;
        self::$flag_gv = 0;
        self::$flag_st = 0;
        self::$flag_lv = 0;
        self::$flag_bo = 0;
        self::$flag_cf = 0;
        self::$flag_cd = 0;
        self::$flag_df = 0;
        self::$flag_cc = 0;
        self::$flag_et = 0;
        self::$flag_len = 0;
        self::$flag_line = 0;
        self::$flag_name = 0;
    }

    public function entropy($file){
        $chars = array();
        $handle = fopen($file, "r");
        $charcount = 0;
        while ($thischar = fread($handle, 1)) {
            if (!isset($chars[ord($thischar)])) {
                $chars[ord($thischar)] = 0;
            }
            $chars[ord($thischar)]++;
            $charcount++;
        }
        $entropy = 0.0;
        $coincidence = 0.0;
        foreach ($chars as $val) {
            $p = $val / $charcount;
            $entropy = $entropy - ($p * log($p,2));
            $coincidence = $coincidence + pow($p,2);
        }
        //echo "entropy:  ".$entropy.",  coincidence:  ".$coincidence."\n";
        $this->getcsvdata("ET",$entropy);
        $this->getcsvdata("CC",$coincidence);
    }

   public function moveArrayItem($array, $from, $to){
    
    if(!empty($array) && count($array) > $from){

       $from_item[] = $array[$from-1];
       array_splice($array,$to,0,$from_item); 
    }

    return $array;

    }

}
