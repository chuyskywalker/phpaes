<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/shared-data.php';

$aescbc = new phpaes\AES_CBC_OpenSSL();
$util   = new phpaes\Util();

foreach ($keys as $keylen => $key) {
    $aescbc->setKey($key);
    $aescbc->setIv($iv);
    foreach ($testTexts as $plainText) {
        $cipherText  = $aescbc->encrypt($plainText);
        $decodedText = $aescbc->decrypt($cipherText);
        echo "-------------------------------------------------------\n";
        echo "               Key: ($keylen) $key\n";
        echo "         Plaintext: $plainText\n";
        echo " Plaintext (bytes): ". $util->bytestring($plainText) ."\n";
        echo "Ciphertext (bytes): ". $util->bytestring($cipherText) ."\n";
        echo "  Ciphertext (len): ". strlen($cipherText) ."\n";
        echo "     Decryptedtext: $decodedText\n";
    }
}
