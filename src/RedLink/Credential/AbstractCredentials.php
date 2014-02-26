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
    public function __construct($endpoint, $version = "1.0-BETA", $apiKey = "") {
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
     * {@inheritdoc}
     */
    public function verify()
    {
        $baseUrl = $this->getEndpoint().$this->getVersion();
        $client = $this->buildUrl($baseUrl);
        
        $response = $client->get('', array('Accept' => 'application/json'))->send();
        $body = $response->getBody(true);
        $arrayBody = json_decode($body, true);
        
        if($response->getStatusCode() == 200) {
            $status = $this->createStatus($arrayBody);
            return $status->isAccesible();
        }
        else {
            $error = $this->createStatusError($arrayBody);
            throw new \RuntimeException("Status check failed: HTTP error code " 
                		. $error->getError() . "\n Endpoint: " . $client->getEndpoint() . "\n Message: " . $error->getMessage());
        }
    
    }
    
    /**
     * <p>Creates a Status instance</p>
     * @param array $arrayBody The array representation of the Application Status
     * @return \RedLink\Status
     */
    private function createStatus($arrayBody) {
        $status = new \RedLink\Status();
        $status->setAccesible(filter_var($arrayBody['accessible'], FILTER_VALIDATE_BOOLEAN));
        $status->setSeconds(filter_var($arrayBody['seconds'], FILTER_VALIDATE_INT));
        $status->setDatasets($arrayBody['datasets']);
        $status->setLimit($arrayBody['limit']);
        $status->setOwner(filter_var($arrayBody['owner'], FILTER_VALIDATE_INT));
        $status->setRequests(filter_var($arrayBody['requests'], FILTER_VALIDATE_INT));
        $status->setBytes(filter_var($arrayBody['bytes'], FILTER_VALIDATE_INT));
        $status->setAnalyses($arrayBody['analyses']);
                
        return $status;
    }
    
    /**
     * <p>Creates a Status Error instance</p>
     * @param array $arrayBody The array representation of the Application Status
     * @return \RedLink\StatusError
     */
    private function createStatusError($arrayBody) {
        $statusError = new \RedLink\StatusError();
        
        $statusError->setAccesible(filter_var($arrayBody['accessible'], FILTER_VALIDATE_BOOLEAN));
        $statusError->setError(filter_var($arrayBody['error'], FILTER_VALIDATE_INT));
        $statusError->setMessage($arrayBody['message']);
        $statusError->setReason($arrayBody['reason']);
        
        return $statusError;
        
    }
}

?>
