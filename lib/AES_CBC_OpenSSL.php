<?php

class AES_CBC_OpenSSL implements Encryption {

    /** @var string */
    private $key;

    /** @var string */
    private $iv;

    /**
     * Create an AES compatible mcrypt instance
     *
     * @param string $key A 16/24/32 byte string
     * @param string $iv A 16byte string
     *
     * @throws InvalidArgumentException
     */
    public function create($key, $iv) {
        if (!in_array(strlen($key), array(16,24,32))) {
            throw new InvalidArgumentException("Key length must be 16, 24, or 32 bytes");
        }
        if (strlen($iv) != 16) {
            throw new InvalidArgumentException("IV length must be 16bytes");
        }
        $this->key = $key;
        $this->iv  = $iv;
    }

    /**
     * @throws LogicException
     * @return string
     */
    public function getIv() {
        if (!isset($this->iv)) {
            throw new LogicException('The iv is not set, call create() prior to usage');
        }
        return $this->iv;
    }

    /**
     * @throws LogicException
     * @return string
     */
    public function getKey() {
        if (!isset($this->key)) {
            throw new LogicException('The key is not set, call create() prior to usage');
        }
        return $this->key;
    }

    /**
     * Encrypt $text
     *
     * Note: This method will pad the text to the AES 16byte block size for
     * you, merely pass in your expected plaintext.
     *
     * Be aware: the returned string is binary content and may contain
     * characters that do not display well. You may wish to further encode
     * this with base64() for transmission and/or storage.
     *
     * @param string $text
     *
     * @throws LogicException
     * @return string
     */
    public function encrypt($text) {
        return openssl_encrypt($text, $this->getMethod(), $this->getKey(), OPENSSL_RAW_DATA, $this->getIv());
    }


    /**
     * Using the given key & iv, decrypt some cipher text
     *
     * Note: This method will automactically remove PKCS#7 padding
     *
     * @param string $cipherText
     *
     * @throws LogicException
     * @return string
     */
    public function decrypt($cipherText) {
        return openssl_decrypt($cipherText, $this->getMethod(), $this->getKey(), OPENSSL_RAW_DATA, $this->getIv());
    }

    /**
     * Transform the key into the bit size and return the openssl mode string
     *
     * @return string
     */
    private function getMethod() {
        return 'aes-'.(8*strlen($this->getKey())).'-cbc';
    }

}
