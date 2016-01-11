<?php

/**
 * Copyright (c) dotBlue (http://dotblue.net)
 */

namespace DotBlue\WebImages;

use Nette;
use Nette\Application;
use Nette\Http;
use Nette\Utils\Image;


class Generator extends Nette\Object
{

	const FORMAT_JPEG = Image::JPEG;
	const FORMAT_PNG = Image::PNG;
	const FORMAT_GIF = Image::GIF;

	/** @var string */
	private $wwwDir;

	/** @var Http\IRequest */
	private $httpRequest;

	/** @var Http\IResponse */
	private $httpResponse;

	/** @var Validator */
	private $validator;

	/** @var IProvider[] */
	private $providers = [];
	
	/** @var integer */
	private $umask;



	public function __construct($wwwDir, $umask, Http\IRequest $httpRequest, Http\IResponse $httpResponse, Validator $validator)
	{
		$this->wwwDir = $wwwDir;
		$this->umask = $umask;
		$this->httpRequest = $httpRequest;
		$this->httpResponse = $httpResponse;
		$this->validator = $validator;
	}



	public function addProvider(IProvider $provider)
	{
		$this->providers[] = $provider;
	}



	/**
	 * @return Validator
	 */
	public function getValidator()
	{
		return $this->validator;
	}



	public function generateImage(ImageRequest $request)
	{
		$width = $request->getWidth();
		$height = $request->getHeight();
		$format = $request->getFormat();

		if (!$this->validator->validate($width, $height)) {
			throw new Application\BadRequestException;
		}

		$image = NULL;
		foreach ($this->providers as $provider) {
			$image = $provider->getImage($request);
			if ($image) {
				break;
			}
		}

		if (!$image) {
			$this->httpResponse->setHeader('Content-Type', 'image/jpeg');
			$this->httpResponse->setCode(Http\IResponse::S404_NOT_FOUND);
			exit;
		}

		$destination = $this->wwwDir . '/' . $this->httpRequest->getUrl()->getRelativeUrl();

		$dirname = dirname($destination);
		if (!is_dir($dirname)) {
			$this->createFolder($dirname);
		}

		$success = $image->save($destination, 90, $format);
		if (!$success) {
			throw new Application\BadRequestException;
		}

		$image->send();
		exit;
	}
	
	
	
	private function createFolder($dirname)
	{
		if ($this->umask) {
			$oldmask = umask(0);
			$success = @mkdir($dirname, octdec($this->umask), TRUE);
			umask($oldmask);
		} else {
			$success = @mkdir($dirname, 0777, TRUE);
		}
		
		if (!$success) {
			throw new Application\BadRequestException;
		}
	}

}
