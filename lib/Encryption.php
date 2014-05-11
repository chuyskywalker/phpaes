<?php

interface Encryption {

    /**
     * Encrypt $data
     *
     * @param string $data
     *
     * @return string
     */
    public function encrypt($data);

    /**
     * Decrypt $ciphertext
     *
     * @param string $ciphertext
     *
     * @return string
     */
    public function decrypt($ciphertext);

}