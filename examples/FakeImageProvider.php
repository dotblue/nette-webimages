<?php

use DotBlue\WebImages\IProvider;
use Nette\Image;


class FakeImageProvider implements IProvider
{

	public function getImage($id, $width, $height, $algorithm = NULL)
	{
		$source = "http://fakeimg.pl/{$width}x{$height}";
		return Image::fromString(file_get_contents($source));
	}

}
