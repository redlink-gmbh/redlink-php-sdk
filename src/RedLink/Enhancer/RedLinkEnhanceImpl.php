<?php

namespace RedLink\Enhancer;

/**
 * 
 * <p>RedLink Enhancer Service</p>
 * 
 * @author Antonio David Pérez Morales <aperez@zaizi.com>
 */
class RedLinkEnhanceImpl extends \RedLink\RedLinkAbstractImpl implements \RedLink\RedLinkEnhance
{

    /**
     * Managed response format
     * @var String
     */
    private static $OUTPUT_FORMAT = "out";
    private static $ACCEPT_FORMAT = "application/rdf+xml";

    /**
     * <p>Default constructor</p>
     * <p>Creates an instance of <code>\RedLink\Enhancer\RedLinkEnhanceImpl</p> using the supplied credentials
     * @param \RedLink\Credentials $credentials the credentials instance
     */
    public function __construct(\RedLink\ICredentials $credentials)
    {
        parent::__construct($credentials);
    }

    /**
     * <p>Enhance the given text returning the annotations found in the text</p>
     * @param String $content The content to be enhanced
     * @param String $analysis The name of the analysis
     */
    public function enhance($content, $analysis)
    {
        $liveApiServiceClient = $this->credentials->buildUrl($this->getEnhanceUri($analysis));
        return $this->execEnhance($liveApiServiceClient, $content);
    }

    /**
     * <p>Obtains the url of the live service using the credentials</p>
     * @param String $analysis The analysis name
     * @return String containing the url of the Live service
     */
    private function getEnhanceUri($analysis)
    {
        $initUrl = $this->initiateUriBuilding();
        return \http_build_url($initUrl, array("path" => self::PATH.DIRECTORY_SEPARATOR.$analysis.DIRECTORY_SEPARATOR.self::ENHANCE, "query" => self::$OUTPUT_FORMAT . "=" . self::$ACCEPT_FORMAT), HTTP_URL_STRIP_AUTH | HTTP_URL_JOIN_PATH | HTTP_URL_JOIN_QUERY | HTTP_URL_STRIP_FRAGMENT);
    }

    /**
     * <p>Executes the enhancement process</p>
     * @param \Guzzle\Http\Client $enhancerClient The client for the enhance service
     * @param String $content The content to enhance
     * 
     * @return \RedLink\Enhancer\Model\Enhancements object containing the enhancements
     */
    private function execEnhance($enhancerClient, $content)
    {
        
        $response = $enhancerClient->post(null, array("Content-Type" => "text/plain", "Accept" => self::$ACCEPT_FORMAT), $content)->send();
        
        if ($response->getStatusCode() != 200) {
            throw new \RuntimeException("Enhancement failed: HTTP error code " . $response->getStatusCode());
        }

        $enhancementsParser = \RedLink\Enhancer\Model\Parser\EnhancementsParserFactory::createDefaultParser($response->getBody(true));
        $enhancements = $enhancementsParser->createEnhancements();

        return $enhancements;
    }

}

?>
