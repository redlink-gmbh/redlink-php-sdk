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
     * <p>Performs an analysis of the content included in the request, getting a Enhancements object as result</p>
     * <p>The analysis result will depend on the configured application within the used Credentials</p>
     * 
     * @param $request \RedLink\Analysis\AnalysisRequest containing the request parameters and the content to be enhanced
     * @return \RedLink\Analysis\Model\Enhancements Structure
     */
    public function enhance(AnalysisRequest $request)
    {
        $liveApiServiceClient = $this->credentials->buildUrl($this->getEnhanceUri($request));
        $rawEnhancements = $this->execEnhance($liveApiServiceClient, $request);

        $enhancementsParser = \RedLink\Analysis\Model\Parser\EnhancementsParserFactory::createDefaultParser($rawEnhancements);
        $enhancements = $enhancementsParser->createEnhancements();

        return $enhancements;
    }

    /**
     * <p>Performs an analysis of the content included in the request, getting the raw response</p>
     * <p>The analysis result will depend on the configured application within the used Credentials</p>
     * 
     * @param $request \RedLink\Analysis\AnalysisRequest} containing the request parameters and the content to be enhanced
     * @return string containing the raw response
     */
    public function enhanceRaw(AnalysisRequest $request)
    {
        $liveApiServiceClient = $this->credentials->buildUrl($this->getEnhanceUri($request, true));
        return $this->execEnhance($liveApiServiceClient, $request, false);
    }

    /**
     * <p>Obtains the url of the live service using the credentials</p>
     * @param \RedLink\Analysis\AnalysisRequest $analysis The analysis request
     * @param boolean $addOutputFormatParam Flag to indicate if the out param must be added the URL or not.
     * @return String containing the url of the Analysis service
     */
    private function getEnhanceUri(AnalysisRequest $analysisRequest, $addOutputFormatParam = false)
    {
        $initUrl = $this->initiateUriBuilding();
        $params = array("path" => self::PATH . DIRECTORY_SEPARATOR . $analysisRequest->getAnalysis() . DIRECTORY_SEPARATOR . self::ENHANCE);
        if ($addOutputFormatParam) {
            $params["query"] = self::$OUTPUT_FORMAT . "=" . $analysisRequest->getOutputFormat();
        }
        return \http_build_url($initUrl, $params , HTTP_URL_STRIP_AUTH | HTTP_URL_JOIN_PATH | HTTP_URL_JOIN_QUERY | HTTP_URL_STRIP_FRAGMENT);
    }

    /**
     * <p>Executes the enhancement process</p>
     * @param \Guzzle\Http\Client $analysisClient The client for the enhance service
     * @param \RedLink\Analysis\AnalysisRequest $request The request containing the content to be enhanced
     * @param Boolean $rdf Flag to indicate if response must be in RDF format in spite of the configured in AnalysisRequest
     * 
     * @return mixed \RedLink\Analysis\Model\Enhancements|array|xml object containing the enhancements
     */
    private function execEnhance($analysisClient, AnalysisRequest $request, $rdf = true)
    {
        $content = $request->isContentString() ? $request->getContent() : file_get_contents($request->getContent());
        $headers = array("Content-Type" => "text/plain");
        if($rdf) {
            $headers["Accept"] = self::$ACCEPT_FORMAT;
        }
        $response = $analysisClient->post(null, $headers , $content)->send();

        if ($response->getStatusCode() != 200) {
            throw new \RuntimeException("Enhancement failed: HTTP error code " . $response->getStatusCode());
        }

        return $response->getBody(true);
    }

}

?>
