<?php

/**
 * Copyright (c) dotBlue (http://dotblue.net)
 */

namespace DotBlue\WebImages;

use Nette\DI;


class Extension extends DI\CompilerExtension
{

	const FORMAT_JPEG = 'jpeg';
	const FORMAT_PNG = 'png';
	const FORMAT_GIF = 'gif';

	/** @var array */
	private $defaults = [
		'routes' => [],
		'prependRoutesToRouter' => TRUE,
		'rules' => [],
		'providers' => [],
		'wwwDir' => '%wwwDir%',
		'format' => self::FORMAT_JPEG,
	];

	/** @var array */
	public $supportedFormats = [
		self::FORMAT_JPEG => Generator::FORMAT_JPEG,
		self::FORMAT_PNG => Generator::FORMAT_PNG,
		self::FORMAT_GIF => Generator::FORMAT_GIF,
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
					];
				} else {
					if (!isset($definition['defaults'])) {
						$definition['defaults'] = [];
					}
				}

				if (!isset($definition['format'])) {
					$definition['format'] = $this->recognizeFormatInMask($definition['mask']) ?: $config['format'];
				}

				if (!isset($this->supportedFormats[$definition['format']])) {
					throw new InvalidConfigException("Format '$definition[format]' isn't supported.");
				}
				$definition['format'] = $this->supportedFormats[$definition['format']];

				$route = $container->addDefinition($this->prefix('route' . $i))
					->setClass('DotBlue\WebImages\Route', [
						$definition['mask'],
						$definition['format'],
						$definition['defaults'],
						$this->prefix('@generator'),
					])
					->addTag($this->prefix('route'))
					->setAutowired(FALSE);

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
			if (!$router) {
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
	private function recognizeFormatInMask($mask)
	{
		$possibleFormats = array_map(function ($format) {
			return '.' . $format;
		}, array_keys($this->supportedFormats));
		if (in_array(substr($mask, -5), $possibleFormats)) {
			return substr($mask, -4);
		} elseif (in_array(substr($mask, -4), $possibleFormats)) {
			return substr($mask, -3);
		}
	}

}

class InvalidConfigException extends \Exception {}
