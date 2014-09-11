<?php

namespace DotBlue\WebImages;

use Nette;


class Rule extends Nette\Object
{

	/** @var int */
	protected $width;


	/** @var int */
	protected $height;


	/** @var int */
	protected $flags;


	/**
	 * @param int
	 * @param int
	 * @param int|NULL
	 */
	public function __construct($width, $height, $flags = NULL)
	{
		$this->width = (int)$width;
		$this->height = (int)$height;
		$this->flags = $flags !== NULL ? (int)$flags : NULL;
	}


	/**
	 * @param int
	 * @param int
	 * @param int|NULL
	 */
	public function validate($width, $height, $flags = NULL)
	{
		return (int)$width === $this->width
			&& (int)$height === $this->height
			&& (!isset($flags) || (int)$flags === $this->flags);
	}

}
