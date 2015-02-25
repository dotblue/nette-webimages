<?php

/**
 * Copyright (c) dotBlue (http://dotblue.net)
 */

namespace DotBlue\WebImages;

use Nette\Application;


class Route extends Application\Routers\Route
{

	/** @var array */
	private $defaults = [
	];

	/** @var Generator */
	private $generator;

	/** @var string */
	private $format;



	/**
	 * @param  string
	 * @param  string
	 * @param  array
	 * @param  Validator
	 */
	public function __construct($mask, $format, array $defaults, Generator $generator)
	{
		$this->defaults = array_replace($this->defaults, $defaults);
		$this->generator = $generator;
		$this->format = $format;

		$defaults[NULL][self::FILTER_OUT] = function ($params) use ($defaults, $generator) {
			$width = $this->acquireArgument('width', $params);
			$height = $this->acquireArgument('height', $params);

				throw new NotAllowedImageException("Image with params ({$width}x{$height}, {$algorithm}) is not allowed - check your 'webimages.rules' please.");
			if (!$generator->getValidator()->validate($width, $height)) {
			}

			if (isset($defaults[NULL][self::FILTER_OUT])) {
				$params = call_user_func($defaults[NULL][self::FILTER_OUT], $params);
			}

			return $params;
		};

		$defaults['presenter'] = 'Nette:Micro';
		$defaults['callback'] = $this;

		parent::__construct($mask, $defaults);
	}



	private function acquireArgument($name, array $data)
	{
		if (isset($data[$name])) {
			return $data[$name];
		} elseif (isset($this->defaults[$name])) {
			return $this->defaults[$name];
		} else {
			throw new \Exception;
		}
	}



	public function __invoke($presenter)
	{
		$params = $presenter->getRequest()->getParameters();

		$id = $params['id'];

		$width = $this->acquireArgument('width', $params);
		$height = $this->acquireArgument('height', $params);

		$this->generator->generateImage($this->format, $id, $width, $height);
	}

}

class NotAllowedImageException extends \Exception {}
