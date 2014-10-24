<?php

/**
 * Test: DotBlue\WebImages\Helpers::prepareArguments()
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same(array(
	'width' => 0,
	'height' => 0,
), DotBlue\WebImages\Helpers::prepareArguments(array()));

Assert::same(array(
	'id' => 1,
	'width' => 0,
	'height' => 0,
), DotBlue\WebImages\Helpers::prepareArguments(array(1)));

Assert::same(array(
	'id' => 1,
	'width' => 2,
	'height' => 0,
), DotBlue\WebImages\Helpers::prepareArguments(array(1, 2)));

Assert::same(array(
	'id' => 1,
	'width' => 2,
	'height' => 3,
), DotBlue\WebImages\Helpers::prepareArguments(array(1, 2, 3)));

Assert::same(array(
	'id' => 1,
	'width' => 2,
	'height' => 3,
	'flags' => 4,
), DotBlue\WebImages\Helpers::prepareArguments(array(1, 2, 3, 4)));

Assert::same(array(
	2,
	'id' => 1,
	'width' => 0,
	'height' => 0,
), DotBlue\WebImages\Helpers::prepareArguments(array(2, 'id' => 1)));
