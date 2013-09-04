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

    public function buildUrl($url);
}

?>
