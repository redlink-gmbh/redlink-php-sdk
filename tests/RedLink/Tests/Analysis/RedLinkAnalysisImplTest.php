<?php

namespace RedLink\Tests\Analysis;

/**
 * <p>RedLinkEnhanceImpl Tests</p>
 *
 * @author Antonio David Pérez Morales <aperez@zaizi.com>
 * @covers RedLink\Analysis\RedLinkAnalysisImpl
 */
class RedLinkAnalysisImplTest extends \PHPUnit_Framework_TestCase
{
    private $redlinkAnalysis;
    
    const TEXT_TO_ENHANCE = "Paris is the capital of France";
    
    protected function setUp()
    {
        parent::setUp();
        $credentials = new \RedLink\Credential\SecureCredentials("8KcDAIk7kfN2JrJfDwxnjOPp4KdL7u0gefac4544");
        $credentials->setSSLCertificates(REDLINK_ROOT_PATH . DIRECTORY_SEPARATOR . 'redlink-CA.pem');
        $this->redlinkAnalysis = new \RedLink\Analysis\RedLinkAnalysisImpl($credentials);
    }
    
    /**
     * @covers \RedLink\Analysis\RedLinkAnalysisImpl\enhance
     */
    public function testEnhance() {
        $analysisRequest = \RedLink\Analysis\AnalysisRequestBuilder::builder()->setAnalysis("alfresco")->setContent(self::TEXT_TO_ENHANCE)->build();
        $enhancements = $this->redlinkAnalysis->enhance($analysisRequest);
        $this->assertNotNull($enhancements);
    }
    
}

?>
