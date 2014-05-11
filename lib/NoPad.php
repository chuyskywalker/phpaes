<?php

class NoPad implements Padder {
    public function pad($data, $block_size) {
        return $data;
    }
    public function unpad($data) {
        return $data;
    }
}
