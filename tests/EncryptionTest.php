<?php

class EncryptionTest extends PHPUnit_Framework_TestCase {

    /**
     * Data provider for tests within
     * @return array
     */
    public function keysAndMessages() {

        $testTexts = array(
            'A',
            'A message',
            'A 15 byte mesag',
            'A 16 byte messag',
            'A 17 byte message',
            'A 31 byte messsage goes in here',
            'A 32 byte messsage 2 get nextpad',
            'utf8-1-ããããããƱ§',
            'utf8-2-ãããããããƱ§',
            'utf8-3-ããããããããƱ§',
            'utf8-4-ãããããããããƱ§',
        );

        // These are, of course, terrible keys, but they are the right length
        $keys = array(
            '16-1' => '1234567890123456',
            '24-1' => '123456789012345678901234',
            '32-1' => '12345678901234567890123456789012',
            // You'll note that the UTF-8 characters cound as 2 bytes, thus the "character" count is one less. This is the correct behavior.
            '16-2' => 'ä34567890123456',
            '24-2' => 'ä3456789012345678901234',
            '32-2' => 'ä345678901234567890123456789012',
        );

        // some ascii iv's, and some UTF8 iv's
        $ivs = array(
            '1234567890123456',
            '6s54df8sef838f6d',
            'ä34567890123456',
            'ääääääää',
        );

        $engines = array(
            new phpaes\AES_CBC_OpenSSL(),
            new phpaes\AES_CBC_Mcrypt(new phpaes\PKCS7()),
        );

        $config = array();
        foreach ($engines as $engine) {
            foreach ($keys as $key) {
                foreach ($testTexts as $text) {
                    foreach ($ivs as $iv) {
                        $config[] = array($engine, $key, $iv, $text);
                    }
                }
            }
        }

        return $config;

    }

    /**
     * Test each engine/key/iv/message combo that encrypt/decryption of messages works
     *
     * @dataProvider keysAndMessages
     */
    public function testEncDecCycle(\phpaes\AES $engine, $key, $iv, $message) {
        $engine->setKey($key);
        $engine->setIv($iv);
        $cipherText  = $engine->encrypt($message);
        $decodedText = $engine->decrypt($cipherText);
        $this->assertSame($message, $decodedText);
    }

    /**
     * Test that encryption works between AES and mcrypt instances
     *
     * @dataProvider keysAndMessages
     */
    public function testCrossEncryption(\phpaes\AES $engine, $key, $iv, $message) {
        $engos = new phpaes\AES_CBC_OpenSSL();
        $engmc = new phpaes\AES_CBC_Mcrypt(new phpaes\PKCS7());
        $engos->setKey($key);
        $engos->setIv($iv);
        $engmc->setKey($key);
        $engmc->setIv($iv);
        $a = $engmc->decrypt(
            $engos->encrypt($message)
        );
        $b = $engos->decrypt(
            $engmc->encrypt($message)
        );
        $this->assertSame($b, $a);
    }

}
