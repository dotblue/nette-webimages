<?php

/**
 * Copyright (c) dotBlue (http://dotblue.net)
 */

namespace DotBlue\WebImages;

use Nette;


class ImageRequest extends Nette\Object
{

	/** @var string */
	private $id;

	/** @var int */
	private $width;

	/** @var int */
	private $height;

	/** @var int */
	private $format;

	/** @var array */
	private $parameters;



	/**
	 * @param  int
	 * @param  string
	 * @param  int
	 * @param  int
	 * @param  array
	 */
	public function __construct($format, $id, $width, $height, array $parameters)
	{
		$this->id = $id;
		$this->width = $width;
		$this->height = $height;
		$this->format = $format;
		$this->parameters = $parameters;
	}



	/**
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
	}



	/**
	 * @return int
	 */
	public function getWidth()
	{
		return $this->width;
	}



	/**
	 * @return int
	 */
	public function getHeight()
	{
		return $this->height;
	}



	/**
	 * @return int
	 */
	public function getFormat()
	{
		return $this->format;
	}



	/**
	 * @return array
	 */
	public function getParameters()
	{
		return $this->parameters;
	}

}
