<?php

namespace RedLink\Credential;

/**
 * <p>Custom Credentials</p>
 *
 * @author Antonio David Pérez Morales <aperez@zaizi.com>
 */
class CustomCredentials extends \RedLink\Credential\AbstractCredentials {
    const DEVELOPMENT_ENDPOINT = "http://localhost:8080/api";
    
    public function __construct($endpoint = null) {
        $ep = $endpoint == null ? self::DEVELOPMENT_ENDPOINT : $endpoint;
        parent::__construct($ep);
    }

    public function buildUrl($url) {
        $url = \http_build_url($url, null, HTTP_URL_STRIP_AUTH | HTTP_URL_JOIN_QUERY | HTTP_URL_STRIP_FRAGMENT);
        $client = new \Guzzle\Http\Client($url);
        
        return $client;
    }

}

?>
