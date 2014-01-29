<?php

namespace RedLink;

/**
 * <p>Abstract base class for all RedLink service implementations</p>
 *
 * @author Antonio David Pérez Morales <aperez@zaizi.com>
 * 
 */
abstract class RedLinkAbstractImpl {

    protected $credentials;

    /**
     * <p>Default constructor</p>
     * 
     * @param \RedLink\Credentials $credentials The <code>\RedLink\Credentials</code> object to be used
     */
    public function __construct(\RedLink\ICredentials $credentials) {
        $this->credentials = $credentials;
    }

    /**
     * <p>Obtains the initial URL to be used</p>
     * @return String the initial URL to be used for the services
     */
    protected final function initiateUriBuilding() {
        return \http_build_url($this->credentials->getEndpoint(), array("path" => DIRECTORY_SEPARATOR.$this->credentials->getVersion()));
    }

}

?>
