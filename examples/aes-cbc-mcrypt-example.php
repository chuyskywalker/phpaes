<?php

require __DIR__ . '/../lib/all.php';
require __DIR__ . '/text-keys.php';

$pkcs7 = new PKCS7();
//$pkcs7 = new NoPad();
$aescbc = new AES_CBC_Mcrypt($pkcs7);

foreach ($keys as $keylen => $key) {
    $aescbc->create($key, $iv);
    foreach ($testTexts as $plainText) {
        $cipherText  = $aescbc->encrypt($plainText);
        $decodedText = $aescbc->decrypt($cipherText);
        echo "-------------------------------------------------------\n";
        echo "               Key: ($keylen) $key\n";
        echo "         Plaintext: $plainText\n";
        echo " Plaintext (bytes): ". bytestring($plainText) ."\n";
        echo "    Padded (bytes): ". bytestring($pkcs7->pad($plainText, 16)) ."\n";
        echo "      Padded (len): ". strlen($pkcs7->pad($plainText, 16)) ."\n";
        echo "Ciphertext (bytes): ". bytestring($cipherText) ."\n";
        echo "  Ciphertext (len): ". strlen($cipherText) ."\n";
        echo "     Decryptedtext: $decodedText\n";
    }
}
