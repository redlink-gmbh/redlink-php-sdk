<?php

namespace RedLink;

/**
 * 
 * <p>RedLink SDK Factory</p>
 * <p>This class eases the creation of the different RedLink service clients. A single client for each configured application should be used</p>
 * 
 * @author Antonio David Pérez Morales <aperez@zaizi.com>
 *
 */
class RedLinkFactory
{

    private static $instance;
    private $credentials = array();

    /**
     * <p>Gets an instance of <code>\RedLink\RedLinkFactory</code></p>
     * @return \RedLink\RedLinkFactory the instance
     */
    public static function getInstance()
    {
        if (!isset(self::$instance) || self::$instance == null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * <p>Gets or creates a credentials for the given key</p>
     * @param string $key The key
     * @return \RedLink\Credentials The credentials
     */
    private function getCredentials($key)
    {
        if (isset($this->credentials[$key])) {
            return $this->credentials[$key];
        } else {
            $c = new \RedLink\Credential\SecureCredentials($key);
            $c->setSSLCertificates(realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'redlink-CA.pem'));
            $this->credentials[$key] = $c;
            return $c;
        }
    }

    /**
     * <p>Creates a new instance of Analysis Service</p>
     * 
     * @param mixed $credentials A String containing the $apiKey or an instance of \RedLink\Credentials
     * @return \RedLink\Analysis\RedLinkAnalysisImpl an instance of <code>\RedLink\Analysis\RedLinkAnalysisImpl</code> 
     */
    public function createAnalysisClient($credentials)
    {
        $c = $credentials;
        if(is_string($credentials)) {
            //$credentials contains the Api Key
            $c = $this->getCredentials($credentials);
        }
        
        return new \RedLink\Analysis\RedLinkAnalysisImpl($c);
    }
    
}

?>
