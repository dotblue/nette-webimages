<?php

/**
 * Copyright (c) dotBlue (http://dotblue.net)
 */

namespace DotBlue\WebImages;

use Nette\Application;


class Route extends Application\Routers\Route
{

	const FORMAT_JPEG = 'jpeg';
	const FORMAT_JPG = 'jpg';
	const FORMAT_PNG = 'png';
	const FORMAT_GIF = 'gif';

	/** @var string|NULL */
	private $id;

	/** @var string|NULL */
	private $format;

	/** @var string */
	private $idParameter = 'id';

	/** @var string|NULL */
	private $formatParameter;

	/** @var array */
	public static $supportedFormats = [
		self::FORMAT_JPEG => Generator::FORMAT_JPEG,
		self::FORMAT_JPG => Generator::FORMAT_JPEG,
		self::FORMAT_PNG => Generator::FORMAT_PNG,
		self::FORMAT_GIF => Generator::FORMAT_GIF,
	];

	/** @var string */
	private $defaults;

	/** @var Generator */
	private $generator;



	/**
	 * @param  string
	 * @param  string
	 * @param  array
	 * @param  Validator
	 */
	public function __construct($mask, array $defaults, Generator $generator, $flags = 0)
	{
		$this->defaults = $defaults;
		$this->generator = $generator;

		$defaults[NULL][self::FILTER_OUT] = function ($parameters) {
			$width = $this->acquireArgument('width', $parameters);
			$height = $this->acquireArgument('height', $parameters);

			if (!$this->generator->getValidator()->validate($width, $height)) {
				throw new NotAllowedImageException("Image with size {$width}x{$height} is not allowed - check your 'webimages.rules' please.");
			}

			if (isset($this->defaults[NULL][self::FILTER_OUT])) {
				$parameters = call_user_func($this->defaults[NULL][self::FILTER_OUT], $parameters);
			}

			return $parameters;
		};

		$defaults['presenter'] = 'Nette:Micro';
		$defaults['callback'] = $this;

		parent::__construct($mask, $defaults, $flags);
	}



	/**
	 * @param  string
	 * @return Route provides a fluent interface
	 */
	public function setId($id)
	{
		$this->id = $id;
		return $this;
	}



	/**
	 * @param  string
	 * @return Route provides a fluent interface
	 */
	public function setIdParameter($parameter)
	{
		if (!$parameter) {
			$parameter = NULL;
		}

		$this->idParameter = $parameter;
		return $this;
	}



	/**
	 * @param  string
	 * @return Route provides a fluent interface
	 */
	public function setFormat($format)
	{
		$this->format = $format;
		return $this;
	}



	/**
	 * @param  string
	 * @return Route provides a fluent interface
	 */
	public function setFormatParameter($parameter)
	{
		if (!$parameter) {
			$parameter = NULL;
		}

		$this->formatParameter = $parameter;
		return $this;
	}



	private function acquireArgument($name, array $data)
	{
		if (isset($data[$name])) {
			return $data[$name];
		} elseif (isset($this->defaults[$name])) {
			return $this->defaults[$name];
		}
	}



	public function __invoke($presenter)
	{
		$parameters = $presenter->getRequest()->getParameters();

		if ($this->formatParameter) {
			if (isset($parameters[$this->formatParameter])) {
				$format = $parameters[$this->formatParameter];
			} else {
				throw new NotAllowedImageException("Format must be specified as parameter, but it's not.");
			}
		} else {
			$format = $this->format;
		}

		if (!isset(self::$supportedFormats[$format])) {
			throw new NotAllowedImageException("Format '$format' is not supported.");
		}
		$format = self::$supportedFormats[$format];

		if (isset($parameters[$this->idParameter])) {
			$id = $parameters[$this->idParameter];
		} else {
			$id = $this->id;
		}

		$this->generator->generateImage(new ImageRequest(
			$format,
			$id,
			$this->acquireArgument('width', $parameters),
			$this->acquireArgument('height', $parameters),
			$parameters
		));
	}

}

class NotAllowedImageException extends Application\BadRequestException {}
