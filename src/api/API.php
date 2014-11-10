<?php
/**
 * NeverBounceAPI PHP
 * /Neverbounce/Api
 *
 */

namespace NeverBounce\API\App;

class App {

	/**
	 * @var string Your NeverBounce secret key
	 */
	protected static $secretKey;

	/**
	 * @var string Your NeverBounce app ID
	 */
	protected static $appID;

	/**
	 * @var string The version of the api to use
	 */
	protected static $version;

	/**
	 * @var string The base url for api requests
	 */
	public static $apiBase = 'https://api.neverbounce.com/';

	/**
	 * Initialize a new API object with secret key, appId and Version
	 *
	 * @param null $secretKey
	 * @param null $appID
	 * @param string $version Defaults to v1
	 */
	public function __construct($secretKey = null, $appID = null, $version = 'v1')
	{
		$this->secretKey = $secretKey;
		$this->apiID = $apiID;
		$this->version = $version;
	}

	//@TODO: make request function
	public function request()
	{

	}

//	@todo: single endpoint
//	@todo: process response
}