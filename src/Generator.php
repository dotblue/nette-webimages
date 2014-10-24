<?php

/**
 * Copyright (c) dotBlue (http://dotblue.net)
 */

namespace DotBlue\WebImages;

use Nette;
use Nette\Http\IRequest;
use DotBlue\WebImages\Validator;
use Nette\Utils\Image;


/**
 * @method \DotBlue\WebImages\Validator getValidator()
 * @method \DotBlue\WebImages\IProvider[] getProviders()
 */
class Generator extends Nette\Object
{

	/** @var string */
	protected $wwwDir;


	/** @var \Nette\Http\IRequest */
	protected $httpRequest;


	/** @var \DotBlue\WebImages\Validator */
	protected $validator;


	/** @var \DotBlue\WebImages\IProvider[] */
	protected $providers = array();


	/**
	 * @param string
	 * @param \Nette\Http\IRequest
	 * @param \DotBlue\WebImages\Validator
	 */
	public function __construct($wwwDir, IRequest $httpRequest, Validator $validator)
	{
		$this->wwwDir = $wwwDir;
		$this->httpRequest = $httpRequest;
		$this->validator = $validator;
	}


	/**
	 * @return \DotBlue\WebImages\Generator
	 */
	public function addProvider(IProvider $provider)
	{
		$this->providers[] = $provider;
		return $this;
	}


	/**
	 * @param string
	 * @param int
	 * @param int
	 * @param int|NULL
	 * @return \Nette\Utils\Image|NULL
	 * @throws \Exception
	 */
	public function generateImage($id, $width, $height, $flags = NULL)
	{
		if (!$this->validator->validate($width, $height, $flags)) {
			throw new \Exception("Image with params ({$width}x{$height}, {$flags}) is not allowed - check your 'webimages.rules' please.");
		}

		$image = NULL;
		foreach ($this->providers as $provider) {
			$image = $provider->getImage($id, $width, $height, $flags);
			if ($image instanceof Image) {
				break;
			}
		}

		if (!$image instanceof Image) {
			throw new \Exception("Image not found.");
		}

		$destination = rtrim($this->wwwDir, '/') . '/' . ltrim($this->httpRequest->getUrl()->getPath(), '/');

		$dirname = dirname($destination);
		if (!is_dir($dirname)) {
			if (!@mkdir($dirname, 0777, TRUE)) {
				throw new \Exception("Cannot create image directory.");
			}
		}

		if (!$image->save($destination)) {
			throw new \Exception("Cannot save image.");
		}

		return $image;
	}

}
