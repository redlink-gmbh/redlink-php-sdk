<?php

namespace RedLink\Credential;

/**
 * <p>Default Credentials</p>
 * <p>Creates the RedLink API Demo Platform Url to be used by the services</p>
 * 
 * @author Antonio David Pérez Morales <adperezmorales@gmail.com>
 */
class DefaultCredentials extends \RedLink\Credential\AbstractCredentials {

    private static $ENDPOINT = "http://demo.api.redlink.io/api";
    private static $KEY_PARAM = "api_key";

    public function __construct($apiKey) {
        parent::__construct(self::$ENDPOINT, $apiKey);
    }

    public function verify() {
        throw new \RuntimeException("unimplemented");
    }

    public function buildUrl($url)  {
        return \http_build_url($url, array("query" => self::$KEY_PARAM."=".$this->getApiKey()), HTTP_URL_JOIN_PATH | HTTP_URL_JOIN_QUERY);
    }
}

?>
