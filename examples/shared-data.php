<?php

// A few good messages to show padding effects
$testTexts = array(
    'A',
    'A message',
    'A 15 byte mesag',
    'A 16 byte messag',
    'A 17 byte message',
    'A 31 byte messsage goes in here',
    'A 32 byte messsage for gives pad',
);

// These are, of course, terrible keys, but they are the right length
$keys = array(
    '16' => '1234567890123456',
    '24' => '123456789012345678901234',
    '32' => '12345678901234567890123456789012',
);

// In practice the IV should always be as unique as possible, but thi is a quick demo
$iv = 'x234567890123456';
//$iv = '0000000000000000';

//echo "IV bytes: " . bytestring($iv) . "\n";
