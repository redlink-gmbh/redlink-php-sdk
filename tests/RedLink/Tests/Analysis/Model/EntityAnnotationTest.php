<?php

namespace RedLink\Tests\Analysis\Model;

/**
 * <p>EntityAnnotation Tests</p>
 *
 * @author Antonio David Pérez Morales <aperez@zaizi.com>
 * 
 * @covers RedLink\Analysis\Model\EntityAnnotation
 */
class EntityAnnotationTest extends \PHPUnit_Framework_TestCase
{

    private static $model;
    private static $entityAnnotation;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        $enhancements = \RedLink\Analysis\Model\Parser\EnhancementsParserFactory::createDefaultParser(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'rdf.txt'))->createEnhancements();
        $entityAnnotations = $enhancements->getEntityAnnotations();
        self::$entityAnnotation = $entityAnnotations['urn:enhancement-0612b7d5-bf5b-62da-9b40-509045ba7651'];
    }

    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();
        self::$model = null;
        self::$entityAnnotation = null;
    }

    /**
     * @covers RedLink\Enhancer\Model\Enhancements::getLanguages
     */
    public function testProperties()
    {

        // Test Entity Type
        $this->assertNotNull(self::$entityAnnotation->getEntityTypes());
        $this->assertCount(10, self::$entityAnnotation->getEntityTypes());
        $this->assertContains("http://rdf.freebase.com/ns/common.topic", self::$entityAnnotation->getEntityTypes());

        //Test Extracted From
        $this->assertNotEmpty(self::$entityAnnotation->getExtractedFrom());

        //Test Confidence
        $this->assertEquals(1, self::$entityAnnotation->getConfidence());

        //Test Entity Label
        $this->assertEquals("CMS", self::$entityAnnotation->getEntityLabel());

        //Test Site
        $this->assertEquals("freebase", self::$entityAnnotation->getSite());

        //Test Entity-Reference
        $this->assertNotNull(self::$entityAnnotation->getEntityReference());
        $this->assertTrue(self::$entityAnnotation->getEntityReference() instanceof \RedLink\Vocabulary\Model\Entity);

        //Test Relations
        $this->assertCount(1, self::$entityAnnotation->getRelations());
        $relations = self::$entityAnnotation->getRelations();
        $firstRelation = array_pop($relations);
        $this->assertTrue($firstRelation instanceof \RedLink\Analysis\Model\TextAnnotation);

        //Test Created
        $this->assertNotNull(self::$entityAnnotation->getCreated());
        $this->assertNotEmpty(self::$entityAnnotation->getCreated());

        //Test Creator
        $this->assertEquals("org.apache.stanbol.enhancer.engines.entitylinking.engine.EntityLinkingEngine", self::$entityAnnotation->getCreator());

        /*
         * 




          <relation xmlns="http://purl.org/dc/terms/" rdf:resource="urn:enhancement-3bd473b2-1cea-1172-264e-3b54e03404f2"/>
         */
    }

}

?>
