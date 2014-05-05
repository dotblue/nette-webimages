<?php

namespace DotBlue\WebImages;

use stdClass;


class Validator
{

	/** @var stdClass[] */
	private $rules = [];



	/**
	 * Adds rule.
	 *
	 * @param  int
	 * @param  int
	 * @param  int
	 */
	public function addRule($width, $height, $algorithm)
	{
		$this->rules[] = (object) [
			'width' => (int) $width,
			'height' => (int) $height,
			'algorithm' => (int) $algorithm,
		];
	}



	/**
	 * Validates whether provided arguments match at least one rule.
	 *
	 * @param  int
	 * @param  int
	 * @param  int
	 * @return bool
	 */
	public function validate($width, $height, $algorithm)
	{
		foreach ($this->rules as $rule) {
			if (
				(int) $width === $rule->width
				&& (int) $height === $rule->height
				&& (
					!isset($algorithm)
					|| (int) $algorithm === $rule->algorithm
				)
			) {
				return TRUE;
			}
		}

		return count($this->rules) > 0 ? FALSE : TRUE;
	}

}
