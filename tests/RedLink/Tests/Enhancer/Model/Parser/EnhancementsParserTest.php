<?php

namespace RedLink\Tests\Enhancer\Model\Parser;

/**
 * <p>EnhancementsParser Tests</p>
 *
 * @author Antonio David Pérez Morales <adperezmorales@gmail.com>
 * @covers RedLink\Enhancer\Model\Parser\EasyRdfEnhancementsParser
 * @covers RedLink\Enhancer\Model\Parser\EnhancementsParserFactory
 */
class EasyRdfEnhancementsParserTest extends \PHPUnit_Framework_TestCase
{
    private static $model;
    private static $enhancementsParser;
    
    
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::$enhancementsParser = \RedLink\Enhancer\Model\Parser\EnhancementsParserFactory::createDefaultParser(file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'rdf.txt'));
        //self::$model->parseFile(__DIR__.DIRECTORY_SEPARATOR.'rdf.txt');
    }
    
    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();
        self::$model = null;
    }
    /**
     * @covers RedLink\Enhancer\Model\Parser\EnhancementsParserFactory::createDefaultParser
     * @covers RedLink\Enhancer\Model\EnhancementsParser::parseEnhancements
     */
    public function testParseEnhancements() {
        $this->assertInstanceOf('\RedLink\Enhancer\Model\Parser\EasyRdfEnhancementsParser', self::$enhancementsParser);
        $enhancementsArray = self::$enhancementsParser->parseEnhancements();
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
        $enhancements = self::$enhancementsParser->createEnhancements();
        
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
