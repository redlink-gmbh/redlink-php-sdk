<?php

namespace RedLink\Tests\Analysis;

/**
 * <p>RedLinkEnhanceImpl Tests</p>
 *
 * @author Antonio David Pérez Morales <aperez@zaizi.com>
 * @author Sergio Fernández <sergio.fernandez@redlink.co>
 * @covers RedLink\Analysis\RedLinkAnalysisImpl
 */
class RedLinkAnalysisImplTest extends \PHPUnit_Framework_TestCase {

    private $redlinkAnalysis;

    private static $API_KEY_VALUE;
    private static $API_ANALYSIS_VALUE;
    private static $REDLINK_CA_FILE;
    
    const TEXT_TO_ENHANCE = "Paris is the capital of France";

    public static function setUpBeforeClass() {
        parent::setUpBeforeClass();
        self::$API_KEY_VALUE = getenv('API_KEY');
        self::$API_ANALYSIS_VALUE = getenv('API_ANALYSIS');
        self::$REDLINK_CA_FILE = REDLINK_ROOT_PATH . DIRECTORY_SEPARATOR . 'redlink-CA.pem';
    }
    
    protected function setUp() {
        parent::setUp();
        $credentials = new \RedLink\Credential\SecureCredentials(self::$API_KEY_VALUE);
        $credentials->setSSLCertificates(self::$REDLINK_CA_FILE);
        $this->redlinkAnalysis = new \RedLink\Analysis\RedLinkAnalysisImpl($credentials);
    }
    
    /**
     * @covers \RedLink\Analysis\RedLinkAnalysisImpl\enhance
     */
    public function testEnhance() {
        $analysisRequest = \RedLink\Analysis\AnalysisRequestBuilder::builder()->setAnalysis(self::$API_ANALYSIS_VALUE)->setContent(self::TEXT_TO_ENHANCE)->build();
        $enhancements = $this->redlinkAnalysis->enhance($analysisRequest);
        $this->assertNotNull($enhancements);
    }
    
}

?>