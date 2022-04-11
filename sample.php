<?php
$a = 'a'.'s'.'s'.'e'.'r'.'t';
$b = 'sss';
function sqlsec($a,$b){
    assert($a);
    echo $a;
    eval($b);
}
sqlsec($a,$b);
base64_decode($a);
$a($b);
?>
