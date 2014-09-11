<?php

/**
 * Test: DotBlue\WebImages\Generator->generateImage()
 */

use Tester\Assert;
use Nette\Utils\Image;


require __DIR__ . '/../bootstrap.php';


class MockProvider implements DotBlue\WebImages\IProvider
{
	public function getImage($id, $width, $height, $flags = NULL)
	{
		return Image::fromBlank($width, $height);
	}
}


$_SERVER['REQUEST_URI'] = 'images/test.jpg';
$requestFactory = new Nette\Http\RequestFactory;
$httpRequest = $requestFactory->createHttpRequest();

$validator = new DotBlue\WebImages\Validator;
$validator->addRule(new DotBlue\WebImages\Rule(1, 2));

$generator = new DotBlue\WebImages\Generator(TEMP_DIR, $httpRequest, $validator);

Assert::exception(function() use ($generator) {
	$generator->generateImage('test.jpg', 3, 3);
}, 'Exception', "Image with params (3x3, ) is not allowed - check your 'webimages.rules' please.");

Assert::exception(function() use ($generator) {
	$generator->generateImage('test.jpg', 1, 2);
}, 'Exception', "Image not found.");

$generator->addProvider(new MockProvider);

$image = $generator->generateImage('test', 1, 2);

Assert::type('Nette\Utils\Image', $image);

Assert::true(file_exists(TEMP_DIR . '/images/test.jpg'));
