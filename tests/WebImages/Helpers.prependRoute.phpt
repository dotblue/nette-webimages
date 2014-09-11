<?php

/**
 * Test: DotBlue\WebImages\Helpers::prependRoute()
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


class MockRouter implements Nette\Application\IRouter
{
	public function match(Nette\Http\IRequest $httpRequest) {}
	public function constructUrl(Nette\Application\Request $appRequest, Nette\Http\Url $refUrl) {}
}


$badRouter = new MockRouter;

$route1 = new Nette\Application\Routers\Route('a');
$route2 = new Nette\Application\Routers\Route('b');

Assert::exception(function() use ($badRouter, $route1) {
	DotBlue\WebImages\Helpers::prependRoute($badRouter, $route1);
}, 'Nette\InvalidArgumentException', "Router must be an instance of Nette\\Application\\Routers\\RouteList.");

$router = new Nette\Application\Routers\RouteList;

DotBlue\WebImages\Helpers::prependRoute($router, $route1);
DotBlue\WebImages\Helpers::prependRoute($router, $route2);

Assert::same(2, count($router));
Assert::same('b', $router[0]->mask);
Assert::same('a', $router[1]->mask);
