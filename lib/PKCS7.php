<?php

/**
 * Implements PKCS#7 Padding
 */
class PKCS7 implements Padder {

    /** @inheritdoc */
    public function pad($data, $block_size) {
        $padding = $block_size - (strlen($data) % $block_size);
        $pattern = chr($padding);
        return $data . str_repeat($pattern, $padding);
    }

    /** @inheritdoc */
    public function unpad($data) {
        $pattern = substr($data, -1);
        $length = ord($pattern);
        $padding = str_repeat($pattern, $length);
        $pattern_pos = strlen($data) - $length;
        if(substr($data, $pattern_pos) == $padding) {
            return substr($data, 0, $pattern_pos);
        }
        return $data;
    }
}
