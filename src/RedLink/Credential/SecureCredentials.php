<?php

namespace RedLink\Credential;

/**
 * <p>Secure Credentials</p>
 * <p>Creates the RedLink API Demo Platform Client to be used by the services over a secure connection</p>
 * 
 * @author Antonio David Pérez Morales <adperezmorales@gmail.com>
 */
class SecureCredentials extends \RedLink\Credential\AbstractCredentials
{

    private static $ENDPOINT = "https://beta.redlink.io/1.0-ALPHA/";
    private static $KEY_PARAM = "key";
    
    /**
     * <p>Keeps the certificate or certificates to be trusted. That is to say, the certificates which the server certificate is going to be validated against</p>
     * <p>If empty, the default certificates (CAs) are used</p>
     * @var string
     */
    private $sslServerCertificate;

    /**
     * <p>SecureCredentials constructor</p>
     * @param string $apiKey the API Key to be used
     */
    public function __construct($apiKey)
    {
        parent::__construct(self::$ENDPOINT, $apiKey);
        $this->sslServerCertificate = '';
    }

    /**
     * {@inheritdoc}
     */
    public function verify()
    {
        throw new \RuntimeException("Not implemented yet");
    }

    /**
     * <p>Set the SSL Server certificates which are going to be trusted</p>
     * @param String $sslServerCertificate The file containing the certificate to trust 
     *      or a path containing the certificates to trust. If empty, the default certificates are used to verify the server certificate with.
     */
    public function setSSLCertificates($sslServerCertificate)
    {
        if(empty($sslServerCertificate))
            $this->sslServerCertificate = '';
        
        if(!is_file($sslServerCertificate) && !is_dir($sslServerCertificate))
            throw new \RuntimeException("The given parameter must be either a valid file/directory or empty in order to use the default CAs");
        
        $this->sslServerCertificate = $sslServerCertificate;
    }
    
    /**
     * <p>Get the SSL Server certificates which are going to be trusted</p>
     * 
     * @return String The file containing the certificate to trust 
     *      or a path containing the certificates to trust. If empty, the default certificates are used to verify the server certificate with.
     */
    public function getSSLCertificates() {
        return $this->sslServerCertificate;
    }

    /**
     * <p>Generates the <code>\Guzzle\Http\Client to call the specified endpoint using the configured certificates</p>
     * @param string $url The url to be used in the new client
     * @return \Guzzle\Http\Client the instance of the client
     */
    public function buildUrl($url)
    {
        $parts = parse_url($url);
        if($parts['scheme'] != "https")
            throw new \RuntimeException("The specified url is not going over HTTPS");
        
        $url = \http_build_url($url, array("query" => self::$KEY_PARAM . "=" . $this->getApiKey()), HTTP_URL_JOIN_PATH | HTTP_URL_JOIN_QUERY);
        
        $curlOptions = array('CURLOPT_SSLVERSION' => 1);
        $client = new \Guzzle\Http\Client($url, array('curl.options' => $curlOptions));
        if(empty($this->sslServerCertificate))
                $client->setSslVerification(true);
        else
            $client->setSslVerification($this->sslServerCertificate);
        
        return $client;
    }

}

?>
