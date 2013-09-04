<?php

namespace RedLink\Credential;

/**
 * <p>Abstract class for the request credentials</p>
 * 
 * @author Antonio David Pérez Morales <adperezmorales@gmail.com>
 * 
 */
abstract class AbstractCredentials implements \RedLink\ICredentials {

    private $endpoint;
    private $apiKey;

    /**
     * <p>The default constructor</p>
     * @param String $endpoint the endpoint
     * @param String $apiKey the api key
     */
    public function __construct($endpoint, $apiKey) {
        $this->endpoint = $endpoint;
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
     * <p>Gets the configured api key</p>
     * @return String the api key
     */
    public function getApiKey() {
        return $this->apiKey;
    }

    /**
     * <p>Verifies the connection with the endpoint</p>
     * @return boolean indicating whether the connection is possible or not
     */
    public function verify() {
        $client = new Guzzle\Http\Client(endpoint);
        $response = $client->head()->send();
        return $response->getStatusCode() == 200;
    }

}

?>
