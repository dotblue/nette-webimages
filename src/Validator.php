<?php

/**
 * Copyright (c) dotBlue (http://dotblue.net)
 */

namespace DotBlue\WebImages;

use Nette;


/**
 * @method \DotBlue\WebImages\Rule[] getRules()
 */
class Validator extends Nette\Object
{

	/** @var \DotBlue\WebImages\Rule[] */
	protected $rules = array();


	/**
	 * @param \DotBlue\WebImages\Rule $rule
	 * @return \DotBlue\WebImages\Validator
	 */
	public function addRule(Rule $rule)
	{
		$this->rules[] = $rule;
		return $this;
	}


	/**
	 * @param int
	 * @param int
	 * @param int|NULL
	 * @return boolean
	 */
	public function validate($width, $height, $flags = NULL)
	{
		foreach ($this->rules as $rule) {
			if ($rule->validate($width, $height, $flags)) {
				return TRUE;
			}
		}

		return count($this->rules) > 0 ? FALSE : TRUE;
	}

}
