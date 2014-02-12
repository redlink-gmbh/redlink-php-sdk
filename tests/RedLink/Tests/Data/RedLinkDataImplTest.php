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
  const DATASET = "sdk-dataset";
  const API_KEY = "IS3LPCRGvEcA7DvmaznLebzNrTJCOU4wb2e63165";
  const QUERY_SELECT = "SELECT * WHERE { ?s ?p ?o }";
  const QUERY_UPDATE = "INSERT DATA { <http://example.org/test> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://example.org/Test> }";

  protected function setUp() {
    parent::setUp();
    //$credentials = new \RedLink\Credential\SecureCredentials("1caZyBJLtZ3MG8x9bZLf14FTEEV25XM7487eb488");
    $credentials = new \RedLink\Credential\SecureCredentials(self::API_KEY);
    $credentials->setSSLCertificates(REDLINK_ROOT_PATH . DIRECTORY_SEPARATOR . 'redlink-CA.pem');
    $this->redlinkDataService = new \RedLink\Data\RedLinkDataImpl($credentials);
  }

  public function testImportDataset() {
    $data = file_get_contents(REDLINK_ROOT_PATH . DIRECTORY_SEPARATOR ."tests/RedLink/Resources/test.rdf");
    $formatObj = \EasyRdf_Format::getFormat('turtle');
    $format = $formatObj->getDefaultMimeType();
    $result = $this->redlinkDataService->importDataset($data, $format,self::DATASET, false);
    $this->assertEquals(true, $result);
  }

  public function testExportDataset() {
    $result = $this->redlinkDataService->exportDataset(self::DATASET);
    //print_r($result->getBody(true));
    $rawModel = $result->getBody(true);
    $model = new \EasyRdf_Graph();
    $model->parse($rawModel);
    echo "Triples:::: ".$model->countTriples();
    $this->assertEquals(200, $result->getStatusCode());
    $this->assertEquals($this->getCurrentSize(), $model->countTriples());
  }

  public function testCleanDataset() {
    $result = $this->redlinkDataService->cleanDataset(self::DATASET);
    $this->assertTrue($result);
    $result = $this->redlinkDataService->exportDataset(self::DATASET);
    //print_r($result->getBody(true));
    $rawModel = $result->getBody(true);
    $model = new \EasyRdf_Graph();
    $model->parse($rawModel);
    echo "Triples after cleaning:::: ".$model->countTriples();
    $this->assertTrue($model->countTriples() == 0);
  }

  public function testSparqlSelect() {
    $result = $this->redlinkDataService->sparqlSelect(self::QUERY_SELECT, self::DATASET);
    $format = \EasyRdf_Format::getFormat('sparql-json');
    $sparqlResultObj = new \EasyRdf_Sparql_Result($result->getBody(true), $format->getDefaultMimeType());
    echo "Number of Rows::".$sparqlResultObj->numRows();
    $this->assertEquals(200, $result->getStatusCode());
  }

  private function getCurrentSize() {
    $result = $this->redlinkDataService->sparqlSelect(self::QUERY_SELECT, self::DATASET);
    $format = \EasyRdf_Format::getFormat('sparql-json');
    $sparqlResultObj = new \EasyRdf_Sparql_Result($result->getBody(true), $format->getDefaultMimeType());
    return $sparqlResultObj->numRows();
  }





}