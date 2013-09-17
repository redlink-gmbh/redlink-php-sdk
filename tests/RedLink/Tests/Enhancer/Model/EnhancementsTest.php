<?php

namespace RedLink\Tests\Enhancer\Model;

/**
 * <p>Enhancements Tests</p>
 *
 * @author Antonio David Pérez Morales <adperezmorales@gmail.com>
 * 
 * @covers RedLink\Enhancer\Model\Enhancements
 */
class EnhancementsTest extends \PHPUnit_Framework_TestCase
{
    private static $model;
    private $enhancements;
    
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
    }
    
    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();
    }
    
    protected function tearDown()
    {
        parent::tearDown();
        $this->enhancements = null;
    }
    
    public function setUp() {
        parent::setUp();
        $this->enhancements = \RedLink\Enhancer\Model\Parser\EnhancementsParserFactory::createDefaultParser(file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'rdf.txt'))->createEnhancements();
    }
    
    /**
     * @covers RedLink\Enhancer\Model\Enhancements::getLanguages
     */
    public function testGetLanguages() {
        
        $this->assertNotNull($this->enhancements->getLanguages());
        $this->assertCount(1, $this->enhancements->getLanguages());
    }
    
    /**
     * @covers RedLink\Enhancer\Model\Enhancements::setLanguages
     */
    public function testSetLanguages() {
        $langs = array("en", "es");
        $this->enhancements->setLanguages($langs);
        $this->assertCount(2, $this->enhancements->getLanguages());
        $this->assertContains("es", $this->enhancements->getLanguages());
    }
    
    /**
     * @covers RedLink\Enhancer\Model\Enhancements::addLanguage
     */
    public function testAddLanguage() {
        $this->enhancements->addLanguage("es");
        $this->assertCount(2, $this->enhancements->getLanguages());
        $this->assertContains("es", $this->enhancements->getLanguages());
    }
    
    /**
     * @covers RedLink\Enhancer\Model\Enhancements::getTextAnnotationsByConfidenceValue
     */
    public function testGetTextAnnotationsByConfidenceValue() {
        $result = $this->enhancements->getTextAnnotationsByConfidenceValue(0.5);
        $this->assertCount(22, $result);
    }
    
    /**
     * @covers RedLink\Enhancer\Model\Enhancements::getEntityAnnotationsByConfidenceValue
     */
    public function testGetEntityAnnotationsByConfidenceValue() {
        $result = $this->enhancements->getEntityAnnotationsByConfidenceValue(0.5);
        $this->assertCount(24, $result);
    }
    
    /**
     * @covers RedLink\Enhancer\Model\Enhancements::getEntitiesByConfidenceValue
     */
    public function testGetEntitiesByConfidenceValue() {
        $result = $this->enhancements->getEntitiesByConfidenceValue(0.5);
        $this->assertCount(24, $result);
    }
    
    /**
     * @covers RedLink\Enhancer\Model\Enhancements::getEntities
     */
    public function testGetEntities() {
        
    }
    
    /**
     * @covers RedLink\Enhancer\Model\Enhancements::getEntity
     */
    public function testGetEntity() {
        $entity = $this->enhancements->getEntity('http://rdf.freebase.com/ns/m.03d60yx');
        $this->assertNotNull($entity);
        $this->assertEquals('http://rdf.freebase.com/ns/m.03d60yx', $entity->getUri());
        $this->assertNotEmpty($entity->getProperties());
    }
    
}

?>
