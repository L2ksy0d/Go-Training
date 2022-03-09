<?php
define(
    'CONFUSE_FUNCTION',
    array_map('strtolower', [
        // function with Confuse code
        'str_replace',
        'chr',
        'base64_decode',
        'str_rot13',
        'mb_strtoupper',
        'strtolower',
        'strtoupper',
        'strtr',
        'substr',
        'gzcompress',
        'gzuncompress',
        'strrev',
        'str_repeat',
        'explode',
        'gzinflate',
        'preg_replace',
        'substr_replace',
        'trim',
        'ucfirst',
        'ucwords'
    ])
);