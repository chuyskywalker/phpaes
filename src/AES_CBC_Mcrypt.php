<?php

namespace phpaes;

class AES_CBC_Mcrypt extends AES {

    /** @var Padder */
    private $padder;

    /** @var resource */
    private $mcryptResource = false;

    function __construct(Padder $padder) {
        $this->padder = $padder;
        // AES always usese RIJNDAEL_128 (16 byte block size)
        //
        // We're using CBC mode because ECB is rather insecure, as
        // demonstrated in this image of "Tux" encrypted with ECB:
        //
        //  - http://upload.wikimedia.org/wikipedia/commons/f/f0/Tux_ecb.jpg
        //
        $this->mcryptResource = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
    }

    /** @inheritdoc */
    public function encrypt($text) {
        $padded_text = $this->padder->pad($text, 16);
        mcrypt_generic_init($this->mcryptResource, $this->getKey(), $this->getIv());
        $cipherText = mcrypt_generic($this->mcryptResource, $padded_text);
        mcrypt_generic_deinit($this->mcryptResource);
        return $cipherText;
    }

    /** @inheritdoc */
    public function decrypt($cipherText) {
        mcrypt_generic_init($this->mcryptResource, $this->getKey(), $this->getIv());
        $decrypted_and_padded_text = mdecrypt_generic($this->mcryptResource, $cipherText);
        mcrypt_generic_deinit($this->mcryptResource);
        $decrypted_text = $this->padder->unpad($decrypted_and_padded_text);
        return $decrypted_text;
    }

}
