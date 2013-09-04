<?php

namespace RedLink\Tests\Credentials;

/**
 * <p>Custom Credentials Tests</p>
 *
 * @author Antonio David Pérez Morales <adperezmorales@gmail.com>
 * @covers RedLink\Credential\CustomCredentials
 */
class CustomCredentialsTest extends \PHPUnit_Framework_TestCase
{
    private $credentials;
    protected function setUp()
    {
        parent::setUp();
        $this->credentials = new \RedLink\Credential\CustomCredentials();
    }
    
    protected function tearDown()
    {
        parent::tearDown();
    }
    
    /**
     * @covers RedLink\Credentials\CustomCredentials::getEndpoint
     */
    public function testEndpoint() {
        $this->assertNotEmpty($this->credentials->getEndpoint());
        $this->assertEquals('http://localhost:8080/api', $this->credentials->getEndpoint());
    }
    
    /**
     * @covers RedLink\Credentials\CustomCredentials::buildUrl
     */
    public function testBuildUrl() {
        $this->assertNotEmpty($this->credentials->buildUrl($this->credentials->getEndpoint()));
        $this->assertEquals('http://localhost:8080/api', $this->credentials->buildUrl($this->credentials->getEndpoint()));
    }
}

?>
