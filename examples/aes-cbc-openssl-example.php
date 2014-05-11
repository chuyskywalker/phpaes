<?php

require __DIR__ . '/../lib/all.php';
require __DIR__ . '/text-keys.php';

$aescbc = new AES_CBC_OpenSSL();

foreach ($keys as $keylen => $key) {
    $aescbc->create($key, $iv);
    foreach ($testTexts as $plainText) {
        $cipherText  = $aescbc->encrypt($plainText);
        $decodedText = $aescbc->decrypt($cipherText);
        echo "-------------------------------------------------------\n";
        echo "               Key: ($keylen) $key\n";
        echo "         Plaintext: $plainText\n";
        echo " Plaintext (bytes): ". bytestring($plainText) ."\n";
        echo "Ciphertext (bytes): ". bytestring($cipherText) ."\n";
        echo "  Ciphertext (len): ". strlen($cipherText) ."\n";
        echo "     Decryptedtext: $decodedText\n";
    }
}
