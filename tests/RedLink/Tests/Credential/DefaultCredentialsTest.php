<?php

namespace RedLink\Tests\Credentials;

/**
 * <p>Default Credentials Tests</p>
 *
 * @author Antonio David Pérez Morales <adperezmorales@gmail.com>
 * @covers RedLink\Credential\DefaultCredentials
 */
class DefaultCredentialsTest extends \PHPUnit_Framework_TestCase
{
    private $credentials;
    private $API_KEY_VALUE = "APIKEYVALUE";
    protected function setUp()
    {
        parent::setUp();
        $this->credentials = new \RedLink\Credential\DefaultCredentials($this->API_KEY_VALUE);
    }
    
    protected function tearDown()
    {
        parent::tearDown();
    }
    
    /**
     * @covers RedLink\Credentials\DefaultCredentials::getEndpoint
     */
    public function testEndpoint() {
        $this->assertNotEmpty($this->credentials->getEndpoint());
        $this->assertEquals('http://demo.api.redlink.io/api', $this->credentials->getEndpoint());
    }
    
    /**
     * @covers RedLink\Credentials\DefaultCredentials::buildUrl
     */
    public function testBuildUrl() {
        $this->assertNotEmpty($this->credentials->buildUrl($this->credentials->getEndpoint()));
        $this->assertEquals('http://demo.api.redlink.io/api?api_key='.$this->API_KEY_VALUE, $this->credentials->buildUrl($this->credentials->getEndpoint()));
    }
}

?>
