<?php

/**
 * Copyright (c) dotBlue (http://dotblue.net)
 */

namespace DotBlue\WebImages;

use Nette;


class Validator extends Nette\Object
{

	/** @var array[] */
	private $rules = [];



	/**
	 * Adds rule.
	 *
	 * @param  int
	 * @param  int
	 */
	public function addRule($width, $height)
	{
		$this->rules[] = [
			'width' => (int) $width,
			'height' => (int) $height,
		];
	}



	/**
	 * Validates whether provided arguments match at least one rule.
	 *
	 * @param  int
	 * @param  int
	 * @return bool
	 */
	public function validate($width, $height)
	{
		foreach ($this->rules as $rule) {
			if ((int) $width === $rule['width'] && (int) $height === $rule['height']) {
				return TRUE;
			}
		}

		return count($this->rules) > 0 ? FALSE : TRUE;
	}



	/**
	 * Returns all added rules.
	 *
	 * @return array[]
	 */
	public function getRules()
	{
		return $this->rules;
	}

}
