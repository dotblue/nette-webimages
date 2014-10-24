<?php

use DotBlue\WebImages\IProvider;
use Nette\Image;


class DefaultImageProvider implements IProvider
{

	/** @var string */
	private $wwwDir;


	public function __construct($wwwDir)
	{
		$this->wwwDir = $wwwDir;
	}


	public function getImage($id, $width, $height, $flags = NULL)
	{
		$path = $this->wwwDir . '/originals/' . $id . '.jpg';

		if (is_file($path)) {
			$image = Image::fromFile($path);
			$image->resize($width, $height, $flags);
			return $image;
		}
	}

}
