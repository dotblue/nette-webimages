<?php

/**
 * Copyright (c) dotBlue (http://dotblue.net)
 */

namespace DotBlue\WebImages;

use Nette\Application;
use Nette\Http;
use Nette\Utils\Image;


class Generator
{

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



	public function __construct($wwwDir, Http\IRequest $httpRequest, Http\IResponse $httpResponse, Validator $validator)
	{
		$this->wwwDir = $wwwDir;
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



	/**
	 * @param  string
	 * @param  int
	 * @param  int
	 * @param  int
	 */
	public function generateImage($id, $width, $height, $algorithm)
	{
		if (!$this->validator->validate($width, $height, $algorithm)) {
			throw new Application\BadRequestException;
		}

		foreach ($this->providers as $provider) {
			$image = $provider->getImage($id, $width, $height, $algorithm);
			if ($image) {
				break;
			}
		}

		if (!$image) {
			$this->httpResponse->setHeader('Content-Type', 'image/jpeg');
			$this->httpResponse->setCode(Http\IResponse::S404_NOT_FOUND);
			exit;
		}

		$destination = $this->wwwDir . '/' . $this->httpRequest->getUrl()->getPath();

		$dirname = dirname($destination);
		if (!is_dir($dirname)) {
			$success = @mkdir($dirname, 0777, TRUE);
			if (!$success) {
				throw new Application\BadRequestException;
			}
		}

		$success = $image->save($destination, 90, Image::JPEG);
		if (!$success) {
			throw new Application\BadRequestException;
		}

		$image->send();
		exit;
	}

}
