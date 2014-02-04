<?php

namespace RedLink\Analysis;

/**
 * 
 * <p>RedLink Analysis Service</p>
 * 
 * @author Antonio David Pérez Morales <aperez@zaizi.com>
 */
class RedLinkAnalysisImpl extends \RedLink\RedLinkAbstractImpl implements \RedLink\RedLinkAnalysis
{

    /**
     * Managed response format
     * @var String
     */
    private static $OUTPUT_FORMAT = "out";
    private static $ACCEPT_FORMAT = "application/rdf+xml";

    /**
     * <p>Default constructor</p>
     * <p>Creates an instance of <code>\RedLink\Enhancer\RedLinkAnalysisImpl</p> using the supplied credentials
     * @param \RedLink\Credentials $credentials the credentials instance
     */
    public function __construct(\RedLink\Credentials $credentials)
    {
        parent::__construct($credentials);
    }

    /**
     * <p>Enhance the given text returning the annotations found in the text</p>
     * @param String $content The content to be enhanced
     * @param String $analysis The name of the analysis
     */
    
    /**
         * <p>Performs an analysis of the content included in the request, getting a Enhancements object as result</p>
         * <p>The analysis result will depend on the configured application within the used Credentials</p>
         * 
         * @param $request \RedLink\Analysis\AnalysisRequest} containing the request parameters and the content to be enhanced
         * @return \RedLink\Analysis\Model\Enhancements Structure
         */
    public function enhance(AnalysisRequest $request)
    {
        $liveApiServiceClient = $this->credentials->buildUrl($this->getEnhanceUri($request->getAnalysis()));
        return $this->execEnhance($liveApiServiceClient, $request);
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
     * @param \Guzzle\Http\Client $analysisClient The client for the enhance service
     * @param \RedLink\Analysis\AnalysisRequest $request The request containing the content to be enhanced
     * 
     * @return \RedLink\Analysis\Model\Enhancements object containing the enhancements
     */
    private function execEnhance($analysisClient, AnalysisRequest $request)
    {
        $content = $request->isContentString() ? $request->getContent() : file_get_contents($request->getContent());
        $response = $analysisClient->post(null, array("Content-Type" => "text/plain", "Accept" => self::$ACCEPT_FORMAT), $content)->send();
        
        if ($response->getStatusCode() != 200) {
            throw new \RuntimeException("Enhancement failed: HTTP error code " . $response->getStatusCode());
        }

        $enhancementsParser = \RedLink\Analysis\Model\Parser\EnhancementsParserFactory::createDefaultParser($response->getBody(true));
        $enhancements = $enhancementsParser->createEnhancements();

        return $enhancements;
    }

}

?>
