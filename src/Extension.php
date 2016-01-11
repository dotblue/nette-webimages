<?php

/**
 * Copyright (c) dotBlue (http://dotblue.net)
 */

namespace DotBlue\WebImages;

use Nette\Application\Routers\Route as NetteRoute;
use Nette\DI;


class Extension extends DI\CompilerExtension
{

	/** @var array */
	private $defaults = [
		'routes' => [],
		'prependRoutesToRouter' => TRUE,
		'rules' => [],
		'providers' => [],
		'wwwDir' => '%wwwDir%',
		'format' => Route::FORMAT_JPEG,
	];



	public function loadConfiguration()
	{
		$container = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);

		$validator = $container->addDefinition($this->prefix('validator'))
			->setClass('DotBlue\WebImages\Validator');

		$generator = $container->addDefinition($this->prefix('generator'))
			->setClass('DotBlue\WebImages\Generator', [
				$config['wwwDir'],
			]);

		foreach ($config['rules'] as $rule) {
			$validator->addSetup('$service->addRule(?, ?)', [
				$rule['width'],
				$rule['height'],
			]);
		}

		if ($config['routes']) {
			$router = $container->addDefinition($this->prefix('router'))
				->setClass('Nette\Application\Routers\RouteList')
				->addTag($this->prefix('routeList'))
				->setAutowired(FALSE);

			$i = 0;
			foreach ($config['routes'] as $route => $definition) {
				if (!is_array($definition)) {
					$definition = [
						'mask' => $definition,
						'defaults' => [],
						'secured' => FALSE,
					];
				} else {
					if (!isset($definition['defaults'])) {
						$definition['defaults'] = [];
					}
					if (!isset($definition['secured'])) {
						$definition['secured'] = FALSE;
					}
				}

				if (!isset($definition['format'])) {
					$definition['format'] = $this->recognizeFormatInMask($definition['mask']) ?: $config['format'];
				}

				if (!isset($definition['format'])) {
					$definition['format'] = $this->recognizeFormatInMask($definition['mask']) ?: $config['format'];
					if (!isset(Route::$supportedFormats[$definition['format']])) {
						throw new InvalidConfigException("Format '$definition[format]' isn't supported.");
					}
				}

				$route = $container->addDefinition($this->prefix('route' . $i))
					->setClass('DotBlue\WebImages\Route', [
						$definition['mask'],
						$definition['defaults'],
						$this->prefix('@generator'),
						$definition['secured'] ? NetteRoute::SECURED : 0,
					])
					->addTag($this->prefix('route'))
					->setAutowired(FALSE);

				if (isset($definition['id'])) {
					if (($parameter = $this->recognizeMaskParameter($definition['id'])) || $parameter === FALSE || $parameter === NULL) {
						$route->addSetup('setIdParameter', [
							$parameter,
						]);
					} else {
						$route->addSetup('setId', [
							$definition['id'],
						]);
					}
				}

				if ($parameter = $this->recognizeMaskParameter($definition['format'])) {
					$route->addSetup('setFormatParameter', [
						$parameter,
					]);
				} else {
					$route->addSetup('setFormat', [
						$definition['format'],
					]);
				}

				$router->addSetup('$service[] = ?', [
					$this->prefix('@route' . $i),
				]);

				$i++;
			}
		}

		if (count($config['providers']) === 0) {
			throw new InvalidConfigException("You have to register at least one IProvider in '" . $this->prefix('providers') . "' directive.");
		}

		foreach ($config['providers'] as $name => $provider) {
			$this->compiler->parseServices($container, [
				'services' => [$this->prefix('provider' . $name) => $provider],
			]);
			$generator->addSetup('addProvider', [$this->prefix('@provider' . $name)]);
		}

		$latte = $container->getDefinition('nette.latteFactory');
		$latte->addSetup('DotBlue\WebImages\Macros::install(?->getCompiler())', ['@self']);
	}



	public function beforeCompile()
	{
		$container = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);

		if ($config['prependRoutesToRouter']) {
			$router = $container->getByType('Nette\Application\IRouter');
			if ($router) {
				if (!$router instanceof DI\ServiceDefinition) {
					$router = $container->getDefinition($router);
				}
			} else {
				$router = $container->getDefinition('router');
			}
			$router->addSetup('DotBlue\WebImages\Helpers::prependRoute', [
				'@self',
				$this->prefix('@router'),
			]);
		}
	}



	/**
	 * @param  string
	 * @return string|NULL
	 */
	private function recognizeMaskParameter($value)
	{
		if ((substr($value, 0, 1) === '<') && (substr($value, -1) === '>')) {
			return substr($value, 1, -1);
		}
	}



	/**
	 * @param  string
	 * @return string|NULL
	 */
	private function recognizeFormatInMask($mask)
	{
		$possibleFormats = array_map(function ($format) {
			return '.' . $format;
		}, array_keys(Route::$supportedFormats));
		if (in_array(substr($mask, -5), $possibleFormats)) {
			return substr($mask, -4);
		} elseif (in_array(substr($mask, -4), $possibleFormats)) {
			return substr($mask, -3);
		}
	}

}

class InvalidConfigException extends \Exception {}
