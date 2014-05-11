<?php

class AES_CBC_Mcrypt implements Encryption {

    /** @var Padder */
    private $padder;

    /** @var resource */
    private $mcryptResource = false;

    /** @var string */
    private $key;

    /** @var string */
    private $iv;

    function __construct(Padder $padder) {
        $this->padder = $padder;
        // AES always usese RIJNDAEL_128 (16 byte block size)
        // We're using CBC mode because ECB is rather insecure, as exampled
        // in this image of "Tux" encrypted with ECB:
        //  - http://upload.wikimedia.org/wikipedia/commons/f/f0/Tux_ecb.jpg
        $this->mcryptResource = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
    }

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
        if ($this->mcryptResource === false) {
            throw new LogicException('You must call create() with a key and iv before encryption');
        }
        $padded_text = $this->padder->pad($text, 16);
        mcrypt_generic_init($this->mcryptResource, $this->key, $this->iv);
        $cipherText = mcrypt_generic($this->mcryptResource, $padded_text);
        mcrypt_generic_deinit($this->mcryptResource);
        return $cipherText;
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
        if ($this->mcryptResource === false) {
            throw new LogicException('You must call create() with a key and iv before decryption');
        }
        mcrypt_generic_init($this->mcryptResource, $this->key, $this->iv);
        $decrypted_and_padded_text = mdecrypt_generic($this->mcryptResource, $cipherText);
        mcrypt_generic_deinit($this->mcryptResource);
        $decrypted_text = $this->padder->unpad($decrypted_and_padded_text);
        return $decrypted_text;
    }

}
