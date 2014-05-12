<?php

namespace phpaes;

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
class AES_CBC_OpenSSL extends AES {

    /** @var string */
    private $aesmode = '';

    private $rawoption;

    public function setKey($key) {
        parent::setKey($key);
        // Transform the key into the bit size and set the openssl mode string
        $this->aesmode = 'aes-'.(8*strlen($key)).'-cbc';
        // in 5.3 the 3rd option to these calls was a boolean for raw/not raw, but became a bitmask in 5.4
        // pick the right variant like this:
        $this->rawoption = defined('OPENSSL_RAW_DATA') ? OPENSSL_RAW_DATA : true;
    }

    /** @inheritdoc */
    public function encrypt($text) {
        return openssl_encrypt($text, $this->aesmode, $this->getKey(), $this->rawoption, $this->getIv());
    }

    /** @inheritdoc */
    public function decrypt($cipherText) {
        return openssl_decrypt($cipherText, $this->aesmode, $this->getKey(), $this->rawoption, $this->getIv());
    }

}
