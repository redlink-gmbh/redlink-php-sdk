<?php

namespace RedLink\Tests\Enhancer\Model;

/**
 * <p>Entity Tests</p>
 *
 * @author Antonio David Pérez Morales <adperezmorales@gmail.com>
 * @covers RedLink\Enhancer\Model\Entity
 */
class EntityTest extends \PHPUnit_Framework_TestCase
{
    private static $entity;
    
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::$entity = new \RedLink\Vocabulary\Model\Entity("http://example.com/1");
        self::$entity->addPropertyValue('http://www.w3.org/1999/02/22-rdf-syntax-ns#type', "http://rdf.freebase.com/ns/common.topic");
        self::$entity->addPropertyValue('http://www.w3.org/1999/02/22-rdf-syntax-ns#type', "http://rdf.freebase.com/ns/music.musical_group");
        self::$entity->addPropertyValue('http://www.w3.org/2000/01/rdf-schema#schema', 'Austria3en', 'en');
        self::$entity->addPropertyValue('http://www.w3.org/2000/01/rdf-schema#schema', 'Austria3de', 'de');
    }
    
    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();
        self::$entity = null;
    }
    /*
     * @covers RedLink\Enhancer\Model\Entity::getUri
     */
    public function testGetUri() {
        $this->assertEquals('http://example.com/1', self::$entity->getUri());
    }
    /**
     * @covers RedLink\Enhancer\Model\Entity::getProperties
     */
    public function testgetProperties() {
        $this->assertNotNull(self::$entity->getProperties());
        $this->assertCount(2, self::$entity->getProperties());
    }
    
    /**
     * @covers RedLink\Enhancer\Model\Entity::addPropertyValue
     */
    public function testAddPropertyValue() {
        $this->assertCount(2, self::$entity->getValues('http://www.w3.org/1999/02/22-rdf-syntax-ns#type'));
        self::$entity->addPropertyValue('http://www.w3.org/1999/02/22-rdf-syntax-ns#type', 'http://rdf.freebase.com/ns/music.artist');
        $this->assertCount(3, self::$entity->getValues('http://www.w3.org/1999/02/22-rdf-syntax-ns#type'));
    }
    
    /**
     * @covers RedLink\Enhancer\Model\Entity::getValues
     */
    public function testGetValues() {
        $this->assertCount(3, self::$entity->getValues('http://www.w3.org/1999/02/22-rdf-syntax-ns#type'));
        $this->assertContains('http://rdf.freebase.com/ns/music.artist', self::$entity->getValues('http://www.w3.org/1999/02/22-rdf-syntax-ns#type'));
    }
    
    /**
     * @covers RedLink\Enhancer\Model\Entity::getValue
     */
    public function testGetValue() {
        $this->assertEquals('Austria3de', self::$entity->getValue('http://www.w3.org/2000/01/rdf-schema#schema', 'de'));
        $this->assertEquals('Austria3en', self::$entity->getValue('http://www.w3.org/2000/01/rdf-schema#schema', 'en'));
    }
    
    /**
     * @covers RedLink\Enhancer\Model\Entity::getFirstPropertyValue
     */
    public function testGetFirstPropertyValue() {
        $this->assertEquals('Austria3en', self::$entity->getFirstPropertyValue('http://www.w3.org/2000/01/rdf-schema#schema'));
    }
    
}

?>
