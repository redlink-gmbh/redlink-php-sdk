<?php

namespace RedLink\Tests;

/**
 * <p>RedLinkEnhanceImpl Tests</p>
 *
 * @author Antonio David Pérez Morales <aperez@zaizi.com>
 * @covers RedLink\RedLinkFactory
 */
class RedLinkFactoryTest extends \PHPUnit_Framework_TestCase
{

    protected function setUp()
    {
        parent::setUp();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function testCreateAnalysisClient()
    {
        $factory = \RedLink\RedLinkFactory::getInstance();
        $serviceClient = $factory->createAnalysisClient("abc");
        $this->assertInstanceOf('\RedLink\RedLinkAnalysis', $serviceClient);
    }
}

?>
