<?php

namespace RedLink\Tests\Enhancer\Model;

/**
 * <p>EnhancementsParser Tests</p>
 *
 * @author Antonio David Pérez Morales <adperezmorales@gmail.com>
 * @covers RedLink\Enhancer\Model\EnhancementsParser
 * @covers RedLink\Enhancer\Model\Enhancements
 */
class EnhancementsParserTest extends \PHPUnit_Framework_TestCase
{
    private static $model;
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::$model = new \EasyRdf_Graph();
        self::$model->parseFile(__DIR__.DIRECTORY_SEPARATOR.'rdf.txt');
    }
    
    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();
        self::$model = null;
    }
    /**
     * @covers RedLink\Enhancer\Model\EnhancementsParser::parseEnhancements
     */
    public function testParseEnhancements() {
        $enhancementsArray = \RedLink\Enhancer\Model\EnhancementsParser::parseEnhancements(self::$model);
        $this->assertNotNull($enhancementsArray);
        $this->assertCount(63, $enhancementsArray);
    }
    
    /**
     * @covers RedLink\Enhancer\Model\EnhancementsParser::createEnhancements
     * @covers RedLink\Enhancer\Model\Enhancements
     */
    public function testCreateEnhancements() {
        /*
         * Test enhancements, text annotations, entity annotations and entities
         */
        $enhancements = \RedLink\Enhancer\Model\EnhancementsParser::createEnhancements(self::$model);
        
        /*
         * Test Enhancements
         */
        $this->assertNotNull($enhancements->getEnhancements());
        $this->assertCount(63, $enhancements->getEnhancements());
        
        /*
         * Test Text Annotations
         */
        $this->assertNotNull($enhancements->getTextAnnotations());
        $this->assertCount(23, $enhancements->getTextAnnotations());
        
        /*
         * Test Entity Annotations
         */
        $this->assertNotNull($enhancements->getEntityAnnotations());
        $this->assertCount(40, $enhancements->getEntityAnnotations());
        
        /*
         * Test Entities
         */
        $this->assertNotNull($enhancements->getEntities());
        $this->assertCount(39, $enhancements->getEntities());
        
        /*
         * Test first text annotation is contained in the enhancements
         */
        $textAnnotations = $enhancements->getTextAnnotations();
        $this->assertContains(array_shift($textAnnotations), $enhancements->getEnhancements());
        
        /*
         * Test first entity annotation is contained in the enhancements
         */
        $entityAnnotations = $enhancements->getEntityAnnotations();
        $firstEntityAnnotation = array_shift($entityAnnotations);
        $this->assertContains($firstEntityAnnotation, $enhancements->getEnhancements());
        
        /*
         * Test entity integrity
         */
        $entity = $firstEntityAnnotation->getEntityReference();
        $this->assertEquals($entity, $enhancements->getEntity($entity->getUri()));
    }
    
}

?>
