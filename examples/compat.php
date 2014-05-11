<?php

require __DIR__ . '/../lib/all.php';
require __DIR__ . '/text-keys.php';

$aesM = new AES_CBC_Mcrypt(new PKCS7());
$aesO = new AES_CBC_OpenSSL();

foreach ($keys as $keylen => $key) {
    $aesM->create($key, $iv);
    $aesO->create($key, $iv);
    foreach ($testTexts as $plainText) {

        // each encrypt
        $cipherTextM  = $aesM->encrypt($plainText);
        $cipherTextO  = $aesO->encrypt($plainText);

        // each decrypt own content
        $decodedTextM  = $aesM->decrypt($cipherTextM);
        $decodedTextO  = $aesO->decrypt($cipherTextO);

        // each decrypt the OTHER's ciphertext
        $decodedTextMx  = $aesM->decrypt($cipherTextO);
        $decodedTextOx  = $aesO->decrypt($cipherTextM);

        echo "-------------------------------------------------------\n";
        echo "                    Key: ($keylen) $key\n";
        echo "              Plaintext: $plainText\n";
        echo "       Same Cipher Text: " . ($cipherTextM === $cipherTextO ? 'TRUE' : 'FALSE') . "\n";
        echo "      Same Decoded Text: " . ($decodedTextM === $decodedTextO ? 'TRUE' : 'FALSE') . "\n";
        echo "Same Cross Decoded Text: " . ($decodedTextMx === $decodedTextOx ? 'TRUE' : 'FALSE') . "\n";

    }
}
