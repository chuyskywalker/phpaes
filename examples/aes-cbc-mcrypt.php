<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/shared-data.php';

$pkcs7 = new phpaes\PKCS7();
//$pkcs7 = new phpaes\NoPad();
$aescbc = new phpaes\AES_CBC_Mcrypt($pkcs7);
$util   = new phpaes\Util();

foreach ($keys as $keylen => $key) {
    $aescbc->setKey($key);
    $aescbc->setIv($iv);
    foreach ($testTexts as $plainText) {
        $cipherText  = $aescbc->encrypt($plainText);
        $padded = $pkcs7->pad($plainText, 16);
        $decodedText = $aescbc->decrypt($cipherText);
        echo "-------------------------------------------------------\n";
        echo "               Key: ($keylen) $key\n";
        echo "         Plaintext: $plainText\n";
        echo " Plaintext (bytes): ". $util->bytestring($plainText) ."\n";
        echo "    Padded (bytes): ". $util->bytestring($padded) ."\n";
        echo "      Padded (hex): ". bin2hex($padded) ."\n";
        echo "      Padded (len): ". strlen($padded) ."\n";
        echo "Ciphertext (bytes): ". $util->bytestring($cipherText) ."\n";
        echo "  Ciphertext (hex): ". bin2hex($cipherText) ."\n";
        echo "  Ciphertext (len): ". strlen($cipherText) ."\n";
        echo "     Decryptedtext: $decodedText\n";
    }
}
