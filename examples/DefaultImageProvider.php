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



	public function getImage(ImageRequest $request)
	{
		$id = $request->getId();
		$width = $request->getWidth();
		$height = $request->getHeight();
		$parameters = $request->getParameters();

		$algorithm = isset($parameters['algorithm'])
			? $parameters['algorithm']
			: self::FIT;
		
		$path = isset($parameters['path'])
			? $parameters['path'] . '/'
			: NULL;

		$path = $this->wwwDir . '/originals/' . $path . $id . '.jpg';

		if (is_file($path)) {
			$image = Image::fromFile($path);
			$image->resize($width, $height, $algorithm);
			return $image;
		}
	}

}
