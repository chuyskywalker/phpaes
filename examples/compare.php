<?php

require __DIR__ . '/../vendor/autoload.php';

$texts = array(
    'short'  => 'Hello, world!',
    'medium' => 'Vivamus fermentum semper porta. Nunc diam velit, adipiscing ut tristique vitae, sagittis vel odio. Maecenas convallis ullamcorper ultricies',
    'long'   => 'Vivamus fermentum semper porta. Nunc diam velit, adipiscing ut tristique vitae, sagittis vel odio. Maecenas convallis ullamcorper ultricies. Curabitur ornare, ligula semper consectetur sagittis, nisi diam iaculis velit, id fringilla sem nunc vel mi. Nam dictum, odio nec pretium volutpat, arcu ante placerat erat, non tristique elit urna et turpis. Quisque mi metus, ornare sit amet fermentum et, tincidunt et orci. Fusce eget orci a orci congue vestibulum. Ut dolor diam, elementum et vestibulum eu, porttitor vel elit. Curabitur venenatis pulvinar tellus gravida ornare. Sed et erat faucibus nunc euismod ultricies ut id justo. Nullam cursus suscipit nisi, et ultrices justo sodales nec. Fusce venenatis facilisis lectus ac semper. Aliquam at massa ipsum. Quisque bibendum purus convallis nulla ultrices ultricies. Nullam aliquam, mi eu aliquam tincidunt, purus velit laoreet tortor, viverra pretium nisi quam vitae mi. Fusce vel volutpat elit. Nam sagittis nisi dui. - Suspendisse lectus leo, consectetur in tempor sit amet, placerat quis neque. Etiam luctus porttitor lorem, sed suscipit est rutrum non. Curabitur lobortis nisl a enim congue semper. Aenean commodo ultrices imperdiet. Vestibulum ut justo vel sapien venenatis tincidunt. Phasellus eget dolor sit amet ipsum dapibus condimentum vitae quis lectus. Aliquam ut massa in turpis dapibus convallis. Praesent elit lacus, vestibulum at malesuada et, ornare et est. Ut augue nunc, sodales ut euismod non, adipiscing vitae orci. Mauris ut placerat justo. Mauris in ultricies enim. Quisque nec est eleifend nulla ultrices egestas quis ut quam. Donec sollicitudin lectus a mauris pulvinar id aliquam urna cursus. Cras quis ligula sem, vel elementum mi. Phasellus non ullamcorper urna.',
);

$keys = array(
    '128' => '1234567890123456',
    '192' => '123456789012345612345678',
    '256' => '12345678901234561234567890123456',
);

$itterations = 500;

$results = array(); // array('ext', 'keylen', 'textsize', '(en/de)code', 'ops/sec')

$aesMcrypt = new phpaes\AES_CBC_Mcrypt(new phpaes\PKCS7());
$aesOpenssl= new phpaes\AES_CBC_OpenSSL();

foreach (array('mcrypt' => $aesMcrypt, 'openssl' => $aesOpenssl) as $methodName => $method) { /** @var phpaes\AES $method */
    foreach ($texts as $textDesc => $secretText) {
        foreach ($keys as $keyLen => $key) {

            $method->setKey($key);
            $method->setIv('1234567890123456'); // non-real iv, but ok for demo

            $s = microtime(true);
            for ($i=$itterations; $i > 0; $i--) {
                $method->encrypt($secretText);
            }
            $e = microtime(true);
            $results[] = array(
                'ext'         => $methodName,
                'keylen'      => $keyLen,
                'textsize'    => $textDesc,
                '(en/de)code' => 'enc',
                'ops/sec'     => number_format($itterations / ($e - $s), 5, '.', '')
            );

            $etext = $method->encrypt($secretText);

            $s = microtime(true);
            for ($i=$itterations; $i > 0; $i--) {
                $method->decrypt($etext);
            }
            $e = microtime(true);
            $results[] = array(
                'ext'         => $methodName,
                'keylen'      => $keyLen,
                'textsize'    => $textDesc,
                '(en/de)code' => 'dec',
                'ops/sec'     => number_format($itterations / ($e - $s), 5, '.', '')
            );

        }
    }
}

$util = new phpaes\Util();
echo "Results:\n" . $util->renderTable($results) . "\n";
