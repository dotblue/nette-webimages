<?php

/**
 * Test: DotBlue\WebImages\Validator
 */

use Tester\Assert;
use Nette\Utils\Image;


require __DIR__ . '/../bootstrap.php';


$validator = new \DotBlue\WebImages\Validator();

Assert::true($validator->validate(100, 100, Image::FIT));

$validator->addRule(4, 3, Image::FIT);
$validator->addRule(16, 10, Image::SHRINK_ONLY);

Assert::same([
	[ 'width' => 4, 'height' => 3, 'algorithm' => Image::FIT ],
	[ 'width' => 16, 'height' => 10, 'algorithm' => Image::SHRINK_ONLY ]
], $validator->getRules());

Assert::true($validator->validate(4, 3, NULL));
Assert::true($validator->validate(4, 3, Image::FIT));
Assert::false($validator->validate(100, 3, Image::FIT));
Assert::false($validator->validate(4, 100, Image::FIT));
Assert::false($validator->validate(4, 3, Image::EXACT));

Assert::true($validator->validate(16, 10, NULL));
Assert::true($validator->validate(16, 10, Image::SHRINK_ONLY));
Assert::false($validator->validate(100, 10, Image::SHRINK_ONLY));
Assert::false($validator->validate(16, 100, Image::SHRINK_ONLY));
Assert::false($validator->validate(16, 10, Image::EXACT));

Assert::false($validator->validate(100, 100, NULL));
Assert::false($validator->validate(100, 100, Image::FIT));
