<?php

class PKCS7Test extends PHPUnit_Framework_TestCase {

    /**
     * @dataProvider blockSizes
     */
    public function testPadUnpad($initialData, $blockSize, $execptedDataLength) {
        $pkcs7 = new phpaes\PKCS7();
        $padded = $pkcs7->pad($initialData, $blockSize);
        $unpadded = $pkcs7->unpad($padded);
        $this->assertSame($initialData, $unpadded);
    }

    /**
     * @dataProvider blockSizes
     */
    public function testReturnsExactBlockSize($initialData, $blockSize, $execptedDataLength) {
        $pkcs7 = new phpaes\PKCS7();
        $padded = $pkcs7->pad($initialData, $blockSize);
        $this->assertSame($execptedDataLength, strlen($padded));
    }

    public function blockSizes() {
        return array(
            array('', 16, 16),
            array('1', 16, 16),
            array('12', 16, 16),
            array('123', 16, 16),
            array('1234', 16, 16),
            array('12345', 16, 16),
            array('123456', 16, 16),
            array('1234567', 16, 16),
            array('12345678', 16, 16),
            array('123456789', 16, 16),
            array('1234567890', 16, 16),
            array('12345678901', 16, 16),
            array('123456789012', 16, 16),
            array('1234567890123', 16, 16),
            array('12345678901234', 16, 16),
            array('123456789012345', 16, 16),
            array('1234567890123456', 16, 32),
            array('12345678901234567', 16, 32),
            array('123456789012345678', 16, 32),
            array('1234567890123456789', 16, 32),
            array('12345678901234567890', 16, 32),
            array('123456789012345678901', 16, 32),
            array('1234567890123456789012', 16, 32),
            array('12345678901234567890123', 16, 32),
            array('123456789012345678901234', 16, 32),
            array('1234567890123456789012345', 16, 32),
            array('12345678901234567890123456', 16, 32),
            array('123456789012345678901234567', 16, 32),
            array('1234567890123456789012345678', 16, 32),
            array('12345678901234567890123456789', 16, 32),
            array('123456789012345678901234567890', 16, 32),
            array('1234567890123456789012345678901', 16, 32),
            array('12345678901234567890123456789012', 16, 48),
            array('123456789012345678901234567890123', 16, 48),

            // some more interesting, but not specifically encryption based pads:
            array('1', 1, 2),
            array('1', 2, 2),
            array('1', 3, 3),

            array('123', 1, 4),
            array('123', 2, 4),
            array('123', 3, 6),
            array('123', 4, 4),
            array('123', 5, 5),

            // some tricky binary padding problems
            array('123456789012' . chr(0) . chr(0), 16, 16),
            array('1234567890123' . chr(0) . chr(0), 16, 16),
            array('12345678901234' . chr(0) . chr(0), 16, 32),

            // this one is good to ensure you're not trimming EXTRA binary
            // characters that were part of the original string -- zero padding
            // falls suspect to this and a bad pkcs would as well.
            array('123456789012' . chr(2) . chr(2), 16, 16)

        );
    }

}
