<?php
namespace RedLink\Tests\Data;
/**
 * Created by JetBrains PhpStorm.
 * User: nmeegama
 * Date: 2/11/14
 * Time: 11:17 AM
 * To change this template use File | Settings | File Templates.
 */

namespace RedLink\Tests\Data;


class RedLinkDataImplTest  extends \PHPUnit_Framework_TestCase {

  private $redlinkDataService;

  private static $API_KEY_VALUE;
  private static $API_DATASET_VALUE;
  private static $REDLINK_CA_FILE;

  const SPARQL_JSON = 'sparql-json';
  const QUERY_SELECT = "SELECT * WHERE { ?s ?p ?o }";
  const QUERY_UPDATE = "INSERT DATA { <http://example.org/test> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://example.org/Test> }";
  const TURTLE = 'turtle';

  public static function setUpBeforeClass() {
    parent::setUpBeforeClass();
    self::$API_KEY_VALUE = getenv('API_KEY');
    self::$API_DATASET_VALUE = getenv('API_DATASET');
    self::$REDLINK_CA_FILE = REDLINK_ROOT_PATH . DIRECTORY_SEPARATOR . 'redlink-CA.pem';
  }

  protected function setUp() {
    parent::setUp();
    $credentials = new \RedLink\Credential\SecureCredentials(self::API_KEY_VALUE);
    $credentials->setSSLCertificates(self::$REDLINK_CA_FILE);
    $this->redlinkDataService = new \RedLink\Data\RedLinkDataImpl($credentials);
  }

  public function testImportDataset() {
    $data = file_get_contents(REDLINK_ROOT_PATH . DIRECTORY_SEPARATOR ."tests/RedLink/Resources/test.rdf");
    $formatObj = \EasyRdf_Format::getFormat(self::TURTLE);
    $format = $formatObj->getDefaultMimeType();
    $result = $this->redlinkDataService->importDataset($data, $format,self::API_DATASET_VALUE, false);
    $this->assertEquals(true, $result);
  }

  public function testExportDataset() {
    $result = $this->redlinkDataService->exportDataset(self::API_DATASET_VALUE);
    //print_r($result->getBody(true));
    $rawModel = $result->getBody(true);
    $model = new \EasyRdf_Graph();
    $model->parse($rawModel);
    echo "Triples:::: " . $model->countTriples();
    $this->assertEquals(200, $result->getStatusCode());
    $this->assertEquals($this->getCurrentSize(), $model->countTriples());
  }

  public function testCleanDataset() {
    $result = $this->redlinkDataService->cleanDataset(self::API_DATASET_VALUE);
    $this->assertTrue($result);
    $result = $this->redlinkDataService->exportDataset(self::API_DATASET_VALUE);
    //print_r($result->getBody(true));
    $rawModel = $result->getBody(true);
    $model = new \EasyRdf_Graph();
    $model->parse($rawModel);
    echo "Triples after cleaning:::: ".$model->countTriples();
    $this->assertTrue($model->countTriples() == 0);
  }

  public function testSparqlSelect() {
    $result = $this->redlinkDataService->sparqlSelect(self::QUERY_SELECT, self::API_DATASET_VALUE);
    $format = \EasyRdf_Format::getFormat(self::SPARQL_JSON);
    $sparqlResultObj = new \EasyRdf_Sparql_Result($result->getBody(true), $format->getDefaultMimeType());
    echo "Number of Rows::".$sparqlResultObj->numRows();
    $this->assertEquals(200, $result->getStatusCode());
  }

  private function getCurrentSize() {
    $result = $this->redlinkDataService->sparqlSelect(self::QUERY_SELECT, self::API_DATASET_VALUE);
    $format = \EasyRdf_Format::getFormat(self::SPARQL_JSON);
    $sparqlResultObj = new \EasyRdf_Sparql_Result($result->getBody(true), $format->getDefaultMimeType());
    return $sparqlResultObj->numRows();
  }

}