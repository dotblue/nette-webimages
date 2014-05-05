<?php

namespace DotBlue\WebImages;

use Nette\Application;


class Route extends Application\Routers\Route
{

	/** @var array */
	private $defaults = [
		'algorithm' => IProvider::FIT,
	];



	/**
	 * @param  string
	 * @param  array
	 * @param  Validator
	 */
	public function __construct($mask, array $defaults, Generator $generator)
	{
		$this->defaults = array_replace($this->defaults, $defaults);

		$defaults[NULL][self::FILTER_OUT] = function ($params) use ($defaults, $generator) {
			$width = $this->acquireArgument('width', $params);
			$height = $this->acquireArgument('height', $params);
			$algorithm = $this->acquireArgument('algorithm', $params);

			if (!$generator->getValidator()->validate($width, $height, $algorithm)) {
				throw new NotAllowedImageException("Image with params ({$width}x{$height}, {$algorithm}) is not allowed - check your 'webimages.rules' please.");
			}

			if (isset($defaults[NULL][self::FILTER_OUT])) {
				$params = call_user_func($defaults[NULL][self::FILTER_OUT], $params);
			}

			return $params;
		};

		$defaults['presenter'] = 'Nette:Micro';
		$defaults['callback'] = function ($presenter) use ($generator) {
			$params = $presenter->getRequest()->getParameters();

			$id = $params['id'];

			$width = $this->acquireArgument('width', $params);
			$height = $this->acquireArgument('height', $params);
			$algorithm = $this->acquireArgument('algorithm', $params);

			$generator->generateImage($id, $width, $height, $algorithm);
		};

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

}

class NotAllowedImageException extends \Exception {}
