<?php
$a = unserialize(base64_decode('czo1OiJoZWxsbyI7'));
$str = preg_replace('/\s\s+/', ' ', 'foo   o');
$s = "czo1OiJoZWxsbyI7";
$b = unserialize(base64_decode($s));
$parr = "sss";
$str = "sssaaabbb";
$d = str_replace($parr,$b,$str);
eval($a);


