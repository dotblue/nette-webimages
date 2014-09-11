<?php

/**
 * Test: DotBlue\WebImages\Latte\Macros n:src
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new \Latte\Engine();
DotBlue\WebImages\Latte\Macros::install($latte->getCompiler());

Assert::matchFile(__DIR__ . '/files/Macros.src.expected.phtml',
	$latte->compile(__DIR__ . '/files/Macros.src.latte'));
