<?php

class AESTest extends PHPUnit_Framework_TestCase {

    /**
     * @dataProvider validKeys
     */
    public function testValidKey($key) {
        $stub = $this->getMockForAbstractClass('\\phpaes\\AES');
        $stub->setKey($key);
        $this->assertAttributeEquals($key, 'key', $stub);
    }

    public function validKeys() {
        return array(
            array('1234567890123456'),
            array('123456789012345612345678'),
            array('12345678901234561234567890123456'),
        );
    }

    /**
     * @dataProvider invalidKeys
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Key length must be 16, 24, or 32 bytes
     */
    public function testInvalidKey($key) {
        $stub = $this->getMockForAbstractClass('\\phpaes\\AES');
        $stub->setKey($key);
    }

    public function invalidKeys() {
        return array(
            array(1),
            array(false),
            array(true),
            array(''),
            array('x1234567890123456'),
            array('x123456789012345612345678'),
            array('x12345678901234561234567890123456'),
        );
    }

}
 