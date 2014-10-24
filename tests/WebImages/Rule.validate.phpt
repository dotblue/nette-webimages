<?php

/**
 * Test: DotBlue\WebImages\Rule->validate()
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$rule1 = new DotBlue\WebImages\Rule(1, 2);

Assert::true($rule1->validate(1, 2));
Assert::false($rule1->validate(3, 2));
Assert::false($rule1->validate(1, 3));
Assert::false($rule1->validate(3, 4));

Assert::false($rule1->validate(1, 2, 0));
Assert::false($rule1->validate(3, 2, 0));
Assert::false($rule1->validate(1, 3, 0));
Assert::false($rule1->validate(3, 4, 0));

$rule2 = new DotBlue\WebImages\Rule(1, 2, 3);

Assert::true($rule2->validate(1, 2));
Assert::false($rule2->validate(4, 2));
Assert::false($rule2->validate(1, 4));
Assert::false($rule2->validate(4, 5));

Assert::false($rule2->validate(1, 2, 0));
Assert::false($rule2->validate(4, 2, 0));
Assert::false($rule2->validate(1, 4, 0));
Assert::false($rule2->validate(4, 5, 0));

Assert::true($rule2->validate(1, 2, 3));
