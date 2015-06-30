<?php

namespace RedLink\Credential;

/**
 * <p>Default Credentials</p>
 * <p>Creates the RedLink API Demo Platform Client to be used by the services</p>
 * 
 * @author Antonio David Pérez Morales <aperez@zaizi.com>
 */
class DefaultCredentials extends \RedLink\Credential\AbstractCredentials {

    private static $ENDPOINT = "https://api.redlink.io/";
    private static $KEY_PARAM = "key";
    private static $API_VERSION = "1.0";

    public function __construct($apiKey) {
        parent::__construct(self::$ENDPOINT, self::$API_VERSION, $apiKey);
        
    }

    public function verify() {
        throw new \RuntimeException("unimplemented");
    }

    public function buildUrl($url)  {
        //return \http_build_url($url, array("query" => self::$KEY_PARAM."=".$this->getApiKey()), HTTP_URL_JOIN_PATH | HTTP_URL_JOIN_QUERY);
        
        $url = \http_build_url($url, array("query" => self::$KEY_PARAM."=".$this->getApiKey()), HTTP_URL_JOIN_PATH | HTTP_URL_JOIN_QUERY);
        $client = new \Guzzle\Http\Client($url);
        
        return $client;
    }
    
}

?>
