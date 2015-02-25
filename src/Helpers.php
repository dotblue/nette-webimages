<?php

namespace DotBlue\WebImages;

use Nette\Application;


class Helpers
{

	public static function prependRoute(Application\Routers\RouteList $router, Application\IRouter $route)
	{
		$router[] = $route;

		$lastKey = count($router) - 1;
		foreach ($router as $i => $r) {
			if ($i === $lastKey) {
				break;
			}
			$router[$i + 1] = $r;
		}

		$router[0] = $route;
	}

}
