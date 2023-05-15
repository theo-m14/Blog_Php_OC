<?php

namespace App\Exception;

use App\Core\HttpRequest;


class NoRouteFoundException extends \Exception
	{
		private HttpRequest $httpRequest;
		
		public function __construct(HttpRequest $httpRequest,string $message = "No route has been found")
		{
			$this->httpRequest = $httpRequest;
			parent::__construct($message);
		}
		
		public function getMoreDetail() : string
		{
			return "Route '" . $this->httpRequest->getUrl() . "' has not been found with method '" . $this->httpRequest->getMethod() . "'";
		}
	}