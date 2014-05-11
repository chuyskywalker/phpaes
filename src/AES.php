<?php

namespace phpaes;

abstract class AES implements Encryption {

    /** @var string */
    private $key;

    /** @var string */
    private $iv;

    /**
     * @param string $iv
     * @throws \InvalidArgumentException
     */
    public function setIv($iv) {
        if (!is_string($iv)) {
            throw new \InvalidArgumentException("IV must be a string");
        }
        if (strlen($iv) != 16) {
            throw new \InvalidArgumentException("IV length must be 16 bytes");
        }
        $this->iv = $iv;
    }

    /**
     * @param string $key
     * @throws \InvalidArgumentException
     */
    public function setKey($key) {
        if (!is_string($key)) {
            throw new \InvalidArgumentException("Key must be a string");
        }
        if (!in_array(strlen($key), array(16,24,32))) {
            throw new \InvalidArgumentException("Key length must be 16, 24, or 32 bytes");
        }
        $this->key = $key;
    }

    /**
     * @throws \LogicException
     * @return string
     */
    public function getIv() {
        if (!isset($this->iv)) {
            throw new \LogicException('The iv is not set, call setIv() prior to usage');
        }
        return $this->iv;
    }

    /**
     * @throws \LogicException
     * @return string
     */
    public function getKey() {
        if (!isset($this->key)) {
            throw new \LogicException('The key is not set, call setKey() prior to usage');
        }
        return $this->key;
    }

}
