<?php

/**
 * Test: DotBlue\WebImages\DI\Extension services
 */

use Tester\Assert;
use Nette\Utils\Image;


require __DIR__ . '/../bootstrap.php';


class NullProvider implements DotBlue\WebImages\IProvider
{
	public function getImage($id, $width, $height, $flags = NULL) {}
}


class BlankProvider implements DotBlue\WebImages\IProvider
{
	public function getImage($id, $width, $height, $flags = NULL)
	{
		return Image::fromBlank($width, $height);
	}
}


$compiler = new Nette\DI\Compiler;
$compiler->addExtension('webimages', new DotBlue\WebImages\DI\Extension);
$container = createContainer($compiler, __DIR__ . '/files/Extension.services.neon');

Assert::type('DotBlue\WebImages\Validator', $container->getService('webimages.validator'));
Assert::type('DotBlue\WebImages\Generator', $container->getService('webimages.generator'));
Assert::type('NullProvider', $container->getService('webimages.provider.one'));
Assert::type('BlankProvider', $container->getService('webimages.provider.0'));
Assert::type('DotBlue\WebImages\Application\Route', $container->getService('webimages.route.0'));
Assert::type('DotBlue\WebImages\Application\Route', $container->getService('webimages.route.1'));
Assert::type('DotBlue\WebImages\Rule', $container->getService('webimages.rule.one'));
Assert::type('DotBlue\WebImages\Rule', $container->getService('webimages.rule.0'));
