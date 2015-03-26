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

	/** @var int|NULL */
	private $width;

	/** @var int|NULL */
	private $height;

	/** @var int */
	private $format;

	/** @var array */
	private $parameters;



	/**
	 * @param  int
	 * @param  string
	 * @param  int|NULL
	 * @param  int|NULL
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
	 * @return int|NULL
	 */
	public function getWidth()
	{
		return $this->width;
	}



	/**
	 * @return int|NULL
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
