<?php

namespace RedLink\Tests\Enhancer;

/**
 * <p>RedLinkEnhanceImpl Tests</p>
 *
 * @author Antonio David Pérez Morales <aperez@zaizi.com>
 * @covers RedLink\Enhancer\RedLinkEnhanceImpl
 */
class RedLinkEnhanceImplTest extends \PHPUnit_Framework_TestCase
{
    private $redlinkEnhancer;
    
    const TEXT_TO_ENHANCE = "Paris is the capital of France";
    
    protected function setUp()
    {
        parent::setUp();
        //$credentials = new \RedLink\Credential\SecureCredentials("1caZyBJLtZ3MG8x9bZLf14FTEEV25XM7487eb488");
        $credentials = new \RedLink\Credential\SecureCredentials("5VnRvvkRyWCN5IWUPhrH7ahXfGCBV8N0197dbccf");
        $credentials->setSSLCertificates(REDLINK_ROOT_PATH . DIRECTORY_SEPARATOR . 'redlink-CA.pem');
        $this->redlinkEnhancer = new \RedLink\Enhancer\RedLinkEnhanceImpl($credentials);
    }
    
    /**
     * @covers \RedLink\Enhancer\RedLinkEnhanceImpl\enhance
     */
    public function testEnhance() {
        $enhancements = $this->redlinkEnhancer->enhance(self::TEXT_TO_ENHANCE, "wordlift");
        $this->assertNotNull($enhancements);
    }
    
}

?>
