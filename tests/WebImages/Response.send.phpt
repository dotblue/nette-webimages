<?php

/**
 * Test: DotBlue\WebImages\Application\Response->send()
 */

use Tester\Assert;
use Nette\Utils\Image;


require __DIR__ . '/../bootstrap.php';


$image = Image::fromBlank(1, 1);
$httpResponse = new Nette\Http\Response;

$response = new DotBlue\WebImages\Application\Response($image, Image::PNG);

ob_start();
$response->send(new Nette\Http\Request(new Nette\Http\UrlScript), $httpResponse);
$result = ob_get_flush();

Assert::same(file_get_contents(__DIR__ . '/files/Response.send.expected.png'), $result);
Assert::same('image/png', $httpResponse->getHeader('Content-Type'));
