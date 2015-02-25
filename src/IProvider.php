<?php

/**
 * Copyright (c) dotBlue (http://dotblue.net)
 */

namespace DotBlue\WebImages;


interface IProvider
{

	const FIT = 0;
	const EXACT = 1;
	const EXACT_HEIGHT_FIT_WIDTH = 2;



	function getImage($id, $width, $height);

}
