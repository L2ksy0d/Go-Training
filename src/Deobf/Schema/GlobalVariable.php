<?php
define(
    'GLOBAL_VAR',
    array_map('strtoupper', [
        '_POST',
        '_GET',
        '_REQUEST',
        '_ENV',
        '_SESSION',
        '_COOKIE',
        '_SERVER'
    ])
);
