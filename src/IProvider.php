<?php

/**
 * Copyright (c) dotBlue (http://dotblue.net)
 */

namespace DotBlue\WebImages;


interface IProvider
{

	/**
	 * @param string
	 * @param int
	 * @param int
	 * @param int|NULL
	 */
	public function getImage($id, $width, $height, $flags = NULL);

}
