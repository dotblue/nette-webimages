<?php

/**
 * Test: DotBlue\WebImages\Validator->addRule()
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$validator = new DotBlue\WebImages\Validator;

Assert::type('DotBlue\WebImages\Validator', $validator->addRule($r1 = new DotBlue\WebImages\Rule(1, 2)));

$validator->addRule($r2 = new DotBlue\WebImages\Rule(3, 4));

Assert::same(array($r1, $r2), $validator->getRules());
