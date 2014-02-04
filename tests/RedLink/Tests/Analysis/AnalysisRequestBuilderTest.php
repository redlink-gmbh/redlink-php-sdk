<?php

namespace RedLink\Tests\Analysis;

/**
 * <p>RedLinkEnhanceImpl Tests</p>
 *
 * @author Antonio David Pérez Morales <aperez@zaizi.com>
 * @covers RedLink\Analysis\AnalysisRequestBuilder
 */
class AnalysisRequestBuilderTest extends \PHPUnit_Framework_TestCase
{

    private $builder;
    private $request;

    protected function setUp()
    {
        parent::setUp();
        $this->builder = \RedLink\Analysis\AnalysisRequestBuilder::builder();
        $this->builder->setAnalysis("test")->setContent("content")->setSummaries(true)->setThumbnails(false);
        $this->request = $this->builder->build();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testRequestBuilder()
    {
        $this->assertInstanceOf('\RedLink\Analysis\AnalysisRequestBuilder', $this->builder);
        $this->assertInstanceOf('\RedLink\Analysis\AnalysisRequest', $this->request);
        $this->assertEquals("test", $this->request->getAnalysis());
        $this->assertEquals("content", $this->request->getContent());
        $this->assertTrue($this->request->getSummary());
        $this->assertFalse($this->request->getThumbnail());
        $this->assertTrue($this->request->isContentString());
        $this->assertEmpty($this->request->getInputFormat());
        $this->assertEmpty($this->request->getOutputFormat());
    }
}

?>
