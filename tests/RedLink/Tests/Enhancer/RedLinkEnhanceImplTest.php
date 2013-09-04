<?php

namespace RedLink\Tests\Enhancer;

/**
 * <p>RedLinkEnhanceImpl Tests</p>
 *
 * @author Antonio David Pérez Morales <adperezmorales@gmail.com>
 * @covers RedLink\Enhancer\RedLinkEnhanceImpl
 */
class RedLinkEnhanceImplTest extends \PHPUnit_Framework_TestCase
{
    private $redlinkEnhancer;
    
    const TEXT_TO_ENHANCE = "Paris is the capital of France";
    
    protected function setUp()
    {
        parent::setUp();
        $this->redlinkEnhancer = new \RedLink\Enhancer\RedLinkEnhanceImpl(new \RedLink\Credential\DefaultCredentials(''));
    }
    
    /**
     * @covers \RedLink\Enhancer\RedLinkEnhanceImpl\enhance
     */
    public function testEnhance() {
        $enhancements = $this->redlinkEnhancer->enhance(self::TEXT_TO_ENHANCE);
        $this->assertNotNull($enhancements);
    }
    
}

?>
