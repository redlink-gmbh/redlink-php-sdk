<?php

namespace RedLink;

/**
 * RedLink SDK Credentials
 * 
 * @author Antonio David Pérez Morales <aperez@zaizi.com>
 *
 */
interface ICredentials {
    
    public function getApiKey();

    public function getEndpoint();

    public function verify();
    
    /**
     * <p>Get the API version</p>
     */
    public function getVersion();

    /**
     * <p>Returns an instance of \Guzzle\Http\Client for the specified url</p>
     */
    public function buildUrl($url);
}

?>
