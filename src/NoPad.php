<?php

namespace phpaes;

/**
 * Class NoPad
 *
 * Applies NO padding to the data and removes nothing.
 *
 * This will cause mcrypt to silently add a default padding for any block
 * cipher modes -- and it doesn't warn you about this problem.
 *
 * So if you're not careful, you'll get bytes not expected.
 */
class NoPad implements Padder {
    public function pad($data, $block_size) {
        return $data;
    }
    public function unpad($data) {
        return $data;
    }
}
