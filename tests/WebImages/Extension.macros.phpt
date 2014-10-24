<?php

/**
 * Test: DotBlue\WebImages\DI\Extension macros
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$compiler = new Nette\DI\Compiler;
$compiler->addExtension('webimages', new DotBlue\WebImages\DI\Extension);
$container = createContainer($compiler, __DIR__ . '/files/Extension.macros.neon');

$latte = $container->getService('nette.latteFactory');

Assert::matchFile(__DIR__ . '/files/Extension.macros.expected.phtml',
	$latte->compile(__DIR__ . '/files/Extension.macros.latte'));
