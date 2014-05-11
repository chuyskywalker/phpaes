<?php

require __DIR__ . '/../lib/all.php';
require __DIR__ . '/text-keys.php';

$aesM = new AES_CBC_Mcrypt(new PKCS7());
//$aesM = new AES_CBC_Mcrypt(new NoPad()); // Try it with this to see what goes wrong!
$aesO = new AES_CBC_OpenSSL();

foreach ($keys as $keylen => $key) {

    $aesM->setKey($key);
    $aesM->setIv($iv);
    $aesO->setKey($key);
    $aesO->setIv($iv);

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
//        echo "    mcrypt cipher bytes: " . bytestring($cipherTextM) . "\n";
//        echo "   openssl cipher bytes: " . bytestring($cipherTextO) . "\n";
        echo "      Same Decoded Text: " . ($decodedTextM === $decodedTextO ? 'TRUE' : 'FALSE') . "\n";
//        echo "    mcrypt decode bytes: " . bytestring($decodedTextM) . "\n";
//        echo "   openssl decode bytes: " . bytestring($decodedTextO) . "\n";
        echo "Same Cross Decoded Text: " . ($decodedTextMx === $decodedTextOx ? 'TRUE' : 'FALSE') . "\n";
//        echo "   mcrypt xdecode bytes: " . bytestring($decodedTextMx) . "\n";
//        echo "  openssl xdecode bytes: " . bytestring($decodedTextOx) . "\n";

    }

}
