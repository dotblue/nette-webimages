<?php

/**
 * Test: DotBlue\WebImages\Validator->validate()
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


class MockRule extends DotBlue\WebImages\Rule
{
	public function validate($width, $height, $flags = NULL)
	{
		Notes::add(array($width, $height, $flags));
		return parent::validate($width, $height, $flags);
	}
}


$validator = new DotBlue\WebImages\Validator;

Assert::true($validator->validate(1, 2));
Assert::true($validator->validate(1, 2, 3));

$validator->addRule($r1 = new MockRule(1, 2));
$validator->addRule($r2 = new MockRule(3, 4, 5));

Assert::true($validator->validate(1, 2));
Assert::false($validator->validate(1, 2, 6));
Assert::true($validator->validate(3, 4));
Assert::true($validator->validate(3, 4, 5));
Assert::false($validator->validate(3, 4, 6));

Assert::false($validator->validate(7, 8));

Assert::same(array(
	array(1, 2, NULL),
	array(1, 2, 6),
	array(1, 2, 6),
	array(3, 4, NULL),
	array(3, 4, NULL),
	array(3, 4, 5),
	array(3, 4, 5),
	array(3, 4, 6),
	array(3, 4, 6),
	array(7, 8, NULL),
	array(7, 8, NULL),
), Notes::fetch());
