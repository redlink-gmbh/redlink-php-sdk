<?php

namespace RedLink\Credential;

/**
 * <p>Abstract class for the request credentials</p>
 * 
 * @author Antonio David Pérez Morales <aperez@zaizi.com>
 * 
 */
abstract class AbstractCredentials implements \RedLink\Credentials {

    protected $endpoint;
    protected $apiKey;
    protected $version;

    /**
     * <p>The default constructor</p>
     * @param String $endpoint the endpoint
     * @param String $version the API version
     * @param String $apiKey the api key
     */
    public function __construct($endpoint, $version = "1.0-ALPHA", $apiKey = "") {
        $this->endpoint = $endpoint;
        $this->version = $version;
        $this->apiKey = $apiKey;
    }

    /**
     * <p>Gets the configured endpoint</p>
     * @return String the endpoint
     */
    public function getEndpoint() {
        return $this->endpoint;
    }

    /**
     * <p>Gets the configured API key</p>
     * @return String the API key
     */
    public function getApiKey() {
        return $this->apiKey;
    }

    /**
     * <p>Gets the API version</p>
     * @return String the API version
     */
    public function getVersion() {
        return $this->version;
    }
    /**
     * <p>Verifies the connection with the endpoint</p>
     * @return boolean indicating whether the connection is possible or not
     */
    public function verify() {
        $client = new Guzzle\Http\Client($this->buildUrl($this->getEndpoint().DIRECTORY_SEPARATOR.$this->getVersion()));
        $response = $client->head()->send();
        return $response->getStatusCode() == 200;
    }

}

?>
