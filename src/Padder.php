<?php

namespace phpaes;

interface Padder {

    /**
     * Pad $data with $block_size bytes of data
     *
     * @param string $data
     * @param int $block_size
     * @return string
     */
    public function pad($data, $block_size);

    /**
     * Remove padding from decrypted $data
     *
     * @param string $data
     * @return string
     */
    public function unpad($data);

}