<?php

/**
 * Test: DotBlue\WebImages\Generator->addProvider()
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


class MockProvider implements DotBlue\WebImages\IProvider
{
	public function getImage($id, $width, $height, $flags = NULL) {}
}


$httpRequest = new Nette\Http\Request(new Nette\Http\UrlScript);
$validator = new DotBlue\WebImages\Validator;
$generator = new DotBlue\WebImages\Generator(TEMP_DIR, $httpRequest, $validator);

Assert::type('DotBlue\WebImages\Generator', $generator->addProvider($p1 = new MockProvider));

$generator->addProvider($p2 = new MockProvider);

Assert::same(array($p1, $p2), $generator->getProviders());
