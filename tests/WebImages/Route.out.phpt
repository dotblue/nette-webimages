<?php

/**
 * Test: DotBlue\WebImages\Application\Route out
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';

require __DIR__ . '/Route.inc';


$url = 'http://example.com/images/foo-1x2.jpg';
$httpRequest = new Nette\Http\Request(new Nette\Http\UrlScript($url));
$generator = new DotBlue\WebImages\Generator(TEMP_DIR, $httpRequest, new DotBlue\WebImages\Validator);

$route1 = new DotBlue\WebImages\Application\Route($generator, 'images/<id>-<width>x<height>.jpg', array());

Assert::same($url, testRouteOut($route1, 'Nette:Micro', array(
	'id' => 'foo',
	'width' => '1',
	'height' => '2',
)));
