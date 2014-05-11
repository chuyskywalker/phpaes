<?php

/**
 * You'll note that this class doesn't do it's own padding.
 *
 * openssl uses pkcs#7 padding by default and as I generally recommend
 * that, I've not provided an option to do any other kind of padding.
 *
 * There isn't anything that stops you, of course, from pre-padding
 * your data with whatever method you like before pushing it into
 * these methods.
 */
class AES_CBC_OpenSSL extends AES_CBC {

    /** @var string */
    private $aesmode = '';

    public function setKey($key) {
        parent::setKey($key);
        // Transform the key into the bit size and return the openssl mode string
        $this->aesmode = 'aes-'.(8*strlen($key)).'-cbc';
    }

    /** @inheritdoc */
    public function encrypt($text) {
        return openssl_encrypt($text, $this->aesmode, $this->getKey(), OPENSSL_RAW_DATA, $this->getIv());
    }

    /** @inheritdoc */
    public function decrypt($cipherText) {
        return openssl_decrypt($cipherText, $this->aesmode, $this->getKey(), OPENSSL_RAW_DATA, $this->getIv());
    }

}
