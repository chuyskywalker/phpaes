<?php

/**
 * Used to ensure that the env is capable of running all of this code
 */
class CompatTest extends PHPUnit_Framework_TestCase {

    public function testHasMcrypt() {
        $this->assertTrue(function_exists('mcrypt_module_open'));
        $this->assertTrue(function_exists('mcrypt_generic_init'));
        $this->assertTrue(function_exists('mcrypt_generic'));
        $this->assertTrue(function_exists('mcrypt_generic_deinit'));
        $this->assertTrue(function_exists('mdecrypt_generic'));
    }

    public function testHasOpenssl() {
        $this->assertTrue(function_exists('openssl_encrypt'));
        $this->assertTrue(function_exists('openssl_decrypt'));
    }

}
 