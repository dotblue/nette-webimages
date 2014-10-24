<?php

/**
 * Test: DotBlue\WebImages\DI\Extension invalid config
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::exception(function() {
	$compiler = new Nette\DI\Compiler;
	$compiler->addExtension('webimages', new DotBlue\WebImages\DI\Extension);
	createContainer($compiler, "
webimages:
    extra: TRUE
parameters:
	wwwDir: ''
");
}, 'Nette\InvalidStateException', "Unknown configuration option webimages.extra.");

Assert::exception(function() {
	$compiler = new Nette\DI\Compiler;
	$compiler->addExtension('webimages', new DotBlue\WebImages\DI\Extension);
	createContainer($compiler, "
webimages:
    rules: TRUE
parameters:
	wwwDir: ''
");
}, 'Nette\Utils\AssertionException', "The option webimages.rules expects to be array, boolean given.");

Assert::exception(function() {
	$compiler = new Nette\DI\Compiler;
	$compiler->addExtension('webimages', new DotBlue\WebImages\DI\Extension);
	createContainer($compiler, "
webimages:
    rules:
        - TRUE
parameters:
	wwwDir: ''
");
}, 'Nette\Utils\AssertionException', "The option webimages.rules.0 expects to be array, boolean given.");

Assert::exception(function() {
	$compiler = new Nette\DI\Compiler;
	$compiler->addExtension('webimages', new DotBlue\WebImages\DI\Extension);
	createContainer($compiler, "
webimages:
    rules:
        - [ extra: TRUE ]
parameters:
	wwwDir: ''
");
}, 'Nette\InvalidStateException', "Unknown configuration option webimages.rules.0.extra.");

Assert::exception(function() {
	$compiler = new Nette\DI\Compiler;
	$compiler->addExtension('webimages', new DotBlue\WebImages\DI\Extension);
	createContainer($compiler, "
webimages:
    rules:
        - [ ]
parameters:
	wwwDir: ''
");
}, 'Nette\Utils\AssertionException', "Missing option webimages.rules.0.width.");

Assert::exception(function() {
	$compiler = new Nette\DI\Compiler;
	$compiler->addExtension('webimages', new DotBlue\WebImages\DI\Extension);
	createContainer($compiler, "
webimages:
    rules:
        - [ width: TRUE ]
parameters:
	wwwDir: ''
");
}, 'Nette\Utils\AssertionException', "The option webimages.rules.0.width expects to be int, boolean given.");

Assert::exception(function() {
	$compiler = new Nette\DI\Compiler;
	$compiler->addExtension('webimages', new DotBlue\WebImages\DI\Extension);
	createContainer($compiler, "
webimages:
    rules:
        - [ width: 1 ]
parameters:
	wwwDir: ''
");
}, 'Nette\Utils\AssertionException', "Missing option webimages.rules.0.height.");

Assert::exception(function() {
	$compiler = new Nette\DI\Compiler;
	$compiler->addExtension('webimages', new DotBlue\WebImages\DI\Extension);
	createContainer($compiler, "
webimages:
    rules:
        - [ width: 1, height: TRUE ]
parameters:
	wwwDir: ''
");
}, 'Nette\Utils\AssertionException', "The option webimages.rules.0.height expects to be int, boolean given.");

Assert::exception(function() {
	$compiler = new Nette\DI\Compiler;
	$compiler->addExtension('webimages', new DotBlue\WebImages\DI\Extension);
	createContainer($compiler, "
webimages:
    rules:
        - [ width: 1, height: 2, flags: TRUE ]
parameters:
	wwwDir: ''
");
}, 'Nette\Utils\AssertionException', "The option webimages.rules.0.flags expects to be int, boolean given.");

Assert::exception(function() {
	$compiler = new Nette\DI\Compiler;
	$compiler->addExtension('webimages', new DotBlue\WebImages\DI\Extension);
	createContainer($compiler, "
webimages:
    providers: TRUE
parameters:
	wwwDir: ''
");
}, 'Nette\Utils\AssertionException', "The option webimages.providers expects to be array, boolean given.");

Assert::exception(function() {
	$compiler = new Nette\DI\Compiler;
	$compiler->addExtension('webimages', new DotBlue\WebImages\DI\Extension);
	createContainer($compiler, "
webimages:
    providers:
        - TRUE
parameters:
	wwwDir: ''
");
}, 'Nette\Utils\AssertionException', "The option webimages.providers.0 expects to be string, boolean given.");

Assert::exception(function() {
	$compiler = new Nette\DI\Compiler;
	$compiler->addExtension('webimages', new DotBlue\WebImages\DI\Extension);
	createContainer($compiler, "
webimages:
    routes: TRUE
parameters:
	wwwDir: ''
services:
	router: Nette\Application\Routers\RouteList
");
}, 'Nette\Utils\AssertionException', "The option webimages.routes expects to be array, boolean given.");

Assert::exception(function() {
	$compiler = new Nette\DI\Compiler;
	$compiler->addExtension('webimages', new DotBlue\WebImages\DI\Extension);
	createContainer($compiler, "
webimages:
    routes:
        - TRUE
parameters:
	wwwDir: ''
services:
	router: Nette\Application\Routers\RouteList
");
}, 'Nette\Utils\AssertionException', "The option webimages.routes.0 expects to be string, boolean given.");

Assert::exception(function() {
	$compiler = new Nette\DI\Compiler;
	$compiler->addExtension('webimages', new DotBlue\WebImages\DI\Extension);
	createContainer($compiler, "
webimages:
    routes:
        - [ ]
parameters:
	wwwDir: ''
services:
	router: Nette\Application\Routers\RouteList
");
}, 'Nette\Utils\AssertionException', "The option webimages.routes.0 expects to be string, integer given.");

Assert::exception(function() {
	$compiler = new Nette\DI\Compiler;
	$compiler->addExtension('webimages', new DotBlue\WebImages\DI\Extension);
	createContainer($compiler, "
webimages:
    routes:
        '': [ extra: TRUE ]
parameters:
	wwwDir: ''
services:
	router: Nette\Application\Routers\RouteList
");
}, 'Nette\InvalidStateException', "Unknown configuration option webimages.routes.0.extra.");

Assert::exception(function() {
	$compiler = new Nette\DI\Compiler;
	$compiler->addExtension('webimages', new DotBlue\WebImages\DI\Extension);
	createContainer($compiler, "
webimages:
    routes:
        '': [ width: TRUE ]
parameters:
	wwwDir: ''
services:
	router: Nette\Application\Routers\RouteList
");
}, 'Nette\Utils\AssertionException', "The option webimages.routes.0.width expects to be int, boolean given.");

Assert::exception(function() {
	$compiler = new Nette\DI\Compiler;
	$compiler->addExtension('webimages', new DotBlue\WebImages\DI\Extension);
	createContainer($compiler, "
webimages:
    routes:
        '': [ height: TRUE ]
parameters:
	wwwDir: ''
services:
	router: Nette\Application\Routers\RouteList
");
}, 'Nette\Utils\AssertionException', "The option webimages.routes.0.height expects to be int, boolean given.");

Assert::exception(function() {
	$compiler = new Nette\DI\Compiler;
	$compiler->addExtension('webimages', new DotBlue\WebImages\DI\Extension);
	createContainer($compiler, "
webimages:
    routes:
        '': [ flags: TRUE ]
parameters:
	wwwDir: ''
services:
	router: Nette\Application\Routers\RouteList
");
}, 'Nette\Utils\AssertionException', "The option webimages.routes.0.flags expects to be int, boolean given.");
