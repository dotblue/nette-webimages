<?php

namespace DotBlue\WebImages;

use Nette\DI;


class Extension extends DI\CompilerExtension
{

	/** @var array */
	private $defaults = [
		'routes' => [],
		'rules' => [],
		'providers' => [],
		'wwwDir' => '%wwwDir%',
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
			$validator->addSetup('$service->addRule(?, ?, ?)', [
				$rule['width'],
				$rule['height'],
				isset($rule['algorithm']) ? $rule['algorithm'] : NULL,
			]);
		}

		$i = 0;
		foreach ($config['routes'] as $route => $defaults) {
			if (!is_array($defaults)) {
				$route = $defaults;
				$defaults = [];
			}

			$route = $container->addDefinition($this->prefix('route' . $i))
				->setClass('DotBlue\WebImages\Route', [
					$route,
					$defaults,
					$this->prefix('@generator'),
				])
				->setAutowired(FALSE);

			$container->getDefinition('router')
				->addSetup('$service[] = ?', [
					$this->prefix('@route' . $i),
				]);

			$i++;
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

		$latte = $container->getDefinition('nette.latte');
		$latte->addSetup('DotBlue\WebImages\Macros::install(?->compiler)', ['@self']);
	}

}

class InvalidConfigException extends \Exception {}
