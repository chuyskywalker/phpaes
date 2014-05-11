<?php

class EncryptionTest extends PHPUnit_Framework_TestCase {

    /**
     * @dataProvider keysAndMessages
     */
    public function testEncDecCycle(\phpaes\AES $engine, $key, $message) {
        $engine->setKey($key);
        $engine->setIv('1234567890123456');
        $cipherText  = $engine->encrypt($message);
        $decodedText = $engine->decrypt($cipherText);
        $this->assertSame($message, $decodedText);
    }

    public function keysAndMessages() {

        $testTexts = array(
            'A',
            'A message',
            'A 15 byte mesag',
            'A 16 byte messag',
            'A 17 byte message',
            'A 31 byte messsage goes in here',
            'A 32 byte messsage 2 get nextpad',
        );

        // These are, of course, terrible keys, but they are the right length
        $keys = array(
            '16' => '1234567890123456',
            '24' => '123456789012345678901234',
            '32' => '12345678901234567890123456789012',
        );

        $engines = array(
            new phpaes\AES_CBC_OpenSSL(),
            new phpaes\AES_CBC_Mcrypt(new phpaes\PKCS7()),
        );

        $config = array();
        foreach ($engines as $engine) {
            foreach ($keys as $key) {
                foreach ($testTexts as $text) {
                    $config[] = array($engine, $key, $text);
                }
            }
        }

        return $config;

    }

    /**
     * @dataProvider keysAndMessages
     */
    public function testCrossEncryption(\phpaes\AES $engine, $key, $message) {
        $engos = new phpaes\AES_CBC_OpenSSL();
        $engmc = new phpaes\AES_CBC_Mcrypt(new phpaes\PKCS7());
        $engos->setKey($key);
        $engos->setIv('1234567890123456');
        $engmc->setKey($key);
        $engmc->setIv('1234567890123456');
        $a = $engmc->decrypt(
            $engos->encrypt($message)
        );
        $b = $engos->decrypt(
            $engmc->encrypt($message)
        );
        $this->assertSame($b, $a);
    }

}
