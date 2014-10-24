<?php

namespace DotBlue\WebImages\Application;

use Nette;
use Nette\Application\IResponse;
use Nette\Utils\Image;
use Nette\Http\IRequest;
use Nette\Http\IResponse as HttpResponse;


/**
 * Image response.
 */
class Response extends Nette\Object implements IResponse
{

	/** @var \Nette\Utils\Image|NULL */
	protected $image;


	/** @var int */
	protected $format;


	/**
	 * @param \Nette\Utils\Image|NULL
	 * @param int
	 */
	public function __construct(Image $image = NULL, $format = Image::JPEG)
	{
		$this->image = $image;
		$this->format = $format;
	}


	public function send(IRequest $httpRequest, HttpResponse $httpResponse)
	{
		if (!$this->image) {
			$httpResponse->setHeader('Content-Type', image_type_to_mime_type($this->format));
			$httpResponse->setCode(HttpResponse::S404_NOT_FOUND);
			return;
		}
		$this->image->send($this->format);
	}

}
