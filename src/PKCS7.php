<?php

namespace phpaes;

/**
 * Implements PKCS#7 Padding
 */
class PKCS7 implements Padder {

    /** @inheritdoc */
    public function pad($data, $block_size) {
        if ($block_size > 256) {
            throw new Exception('$block_size may not be more than 256');
        }
        $padding = $block_size - (Util::encryption_strlen($data) % $block_size);
        $pattern = chr($padding);
        return $data . str_repeat($pattern, $padding);
    }

    /** @inheritdoc */
    public function unpad($data) {
        // find the last character
        $padChar = substr($data, -1);
        // transform it back to the int of how much the string was padded
        $padLength = ord($padChar);
        // return just the text without that many bytes from the end
        return substr($data, 0, -$padLength);
    }

}
