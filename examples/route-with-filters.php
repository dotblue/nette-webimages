<?php

$router = $container->getService('router');

$route = new DotBlue\WebImages\Application\Route('images/<id>-<width>x<height>.jpg', array(
	'id' => array(
		DotBlue\WebImages\Application\Route::FILTER_IN => function($slug) {
			// ...
		},
		DotBlue\WebImages\Application\Route::FILTER_OUT => function($id) {
			// ...
		},
	),
), $container->getByType('DotBlue\WebImages\Generator'));

DotBlue\WebImages\Helpers::prependRoute($router, $route);
