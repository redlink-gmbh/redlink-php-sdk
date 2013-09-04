<?php

namespace RedLink\Enhancer;

/**
 * 
 * <p>RedLink Enhancer Service</p>
 * 
 * @author Antonio David Pérez Morales <aperez@zaizi.com>
 */
class RedLinkEnhanceImpl extends \RedLink\RedLinkAbstractImpl implements \RedLink\RedLinkEnhance {

    /**
     * Managed response format
     * @var String
     */
    private static $FORMAT = "rdf";
    
    /**
     * <p>Default constructor</p>
     * <p>Creates an instance of <code>\RedLink\Enhancer\RedLinkEnhanceImpl</p> using the supplied credentials
     * @param \RedLink\Credentials $credentials the credentials instance
     */
    public function __construct(\RedLink\ICredentials $credentials) {
        parent::__construct($credentials);
    }

    /**
     * <p>Enhance the given text returning the annotations found in the text</p>
     * @param String $content The content to be enhanced
     */
    public function enhance($content) {
        
        $liveApiServiceUrl = $this->credentials->buildUrl($this->getLiveUrl());
        return $this->execEnhance($liveApiServiceUrl, $content);
        /* try
        {
            String service = credentials.buildUrl(getLiveUriBuilder()).toString();
            return execEnhance(service, content);
        }
        catch (MalformedURLException | IllegalArgumentException | UriBuilderException e)
        {
            throw new RuntimeException(e);
        }*/
        
     /*   private final UriBuilder getLiveUriBuilder()
    {
        return initiateUriBuilding().path(LIVE).queryParam(LIVE_FORMAT, FormatHelper.getLabel(format));
    }*/
    }
    
    /**
     * <p>Obtains the url of the live service using the credentials</p>
     * @return String containing the url of the Live service
     */
    private function getLiveUrl() {
        $initUrl = $this->initiateUriBuilding();
        return \http_build_url($initUrl, array("path" => self::LIVE, "query" => self::LIVE_FORMAT."=".self::$FORMAT), HTTP_URL_STRIP_AUTH | HTTP_URL_JOIN_PATH | HTTP_URL_JOIN_QUERY | HTTP_URL_STRIP_FRAGMENT);
    }
    
    /**
     * <p>Executes the enhancement process</p>
     * @param String $serviceUrl The url of the enhance (live) service
     * @param String $content  The content to enhance
     * 
     * @return \RedLink\Enhancer\Model\Enhancements object containing the enhancements
     */
    private function execEnhance($serviceUrl, $content) {
        $client = new \Guzzle\Http\Client($serviceUrl);
        $response = $client->post(null, array("Content-Type" => "text/plain"), $content)->send();
        
        //return $response;
        
        if($response->getStatusCode() != 200) {
            throw new \RuntimeException("Enhancement failed: HTTP error code " . $response->getStatusCode());
        }
        
        $model = new \EasyRdf_Graph();
        $model->parse($response->getBody(true));
        $enhancements = \RedLink\Enhancer\Model\EnhancementsParser::createEnhancements($model);
        
        return $enhancements;
    }

}

?>
