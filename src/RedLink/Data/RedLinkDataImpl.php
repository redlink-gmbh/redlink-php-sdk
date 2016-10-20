<?php
namespace RedLink\Data;
/**
 *
 * <p>RedLink Data Service</p>
 */
class RedLinkDataImpl extends \RedLink\RedLinkAbstractImpl implements \RedLink\IData {
  /**
   * The SPARQL UPDATE Media type
   * @var string
   */
  private static $SPARQL_UPDATE_FORMAT = "application/sparql-update";

  /**
   * Default Constructor
   * Genrates an instance of \RedLink\Data\RedLinkDataImpl
   * @param \RedLink\ICredentials $credentials the credentials instance
   */
  public function __construct(\RedLink\Credentials $credentials) {
    parent::__construct($credentials);
  }

  /**
   * Uploading a dataset to the RedLink Platform
   * @param string $data
   * @param string $format
   * @param string $dataset
   * @param bool $cleanBefore
   * @return bool Returns TRUE if the request was successful else returns FALSE
   */
  public function importDataset($data, $format = '', $dataset = '', $cleanBefore = FALSE) {
    $dataServiceClient = $this->credentials->buildUrl($this->getDataUri($dataset));
    if (empty($format)) {
      $formatObj = \EasyRdf_Format::getFormat('turtle');
      $format = $formatObj->getDefaultMimeType();
    }
    $contentFormatObj = \EasyRdf_Format::getFormat('rdfxml');

    if ($cleanBefore) {
      $response = $dataServiceClient
        ->put(NULL, array(
          "Content-Type" => $contentFormatObj->getDefaultMimeType(),
          "Accept" => $format
        ), $data)->send();
    }
    else {
      $response = $dataServiceClient
        ->post(NULL, array(
          "Content-Type" => $contentFormatObj->getDefaultMimeType(),
          "Accept" => $format
        ), $data)->send();
    }

    return ($response->getStatusCode() == 200);

  }

  /**
   * Retrieve RDF module of the dataset from the RedLink platform
   * @param string $dataset
   * @return mixed|void
   * @throws \RuntimeException
   */
  public function exportDataset($dataset) {
    $dataServiceClient = $this->credentials->buildUrl($this->getDataUri($dataset));
    $format = \EasyRdf_Format::getFormat('turtle');
    $response = $dataServiceClient
      ->get(NULL, array(
        "Content-Type" => "text/turtle",
        "Accept" => $format->getDefaultMimeType()
      ))->send();

    if ($response->getStatusCode() == 200) {
//      $rawModel = $response->getBody(true);
//      $model = new \EasyRdf_Graph();
//      $model->parse($rawModel);
      return $response;
      //todo
    }
    else {
      throw new \RuntimeException("Dataset Exporting Failed: HTTP error code " . $response->getStatusCode());
    }

  }

  /**
   * Delete a dataset from the RedLink platform
   * @param string $dataset
   * @return bool|mixed
   */
  public function cleanDataset($dataset) {
    $dataServiceClient = $this->credentials->buildUrl($this->getDataUri($dataset));
    $response = $dataServiceClient->delete()->send();
    return ($response->getStatusCode() == 200);
  }

  /**
   * Get a resource from the Redlink Platform
   * @param $resource
   * @param string $dataset
   * @return mixed|void
   * @throws \RuntimeException
   */
  public function getRescouce($resource, $dataset = '') {
    $resourceServiceClient = $this->credentials->buildUrl($this->getResourceUri($resource, $dataset));
    $format = \EasyRdf_Format::getFormat('turtle');
    $response = $resourceServiceClient
      ->get(NULL, array(
        "Content-Type" => "text/plain",
        "Accept" => $format->getDefaultMimeType()
      ))->send();
    if ($response->getStatusCode() == 200) {
      //todo
    }
    else {
      if ($response->getStatusCode() == 404) {
        //todo
      }
      else {
        throw new \RuntimeException("Unexpected error retrieving resource " . $response->getStatusCode());
      }
    }

  }

  /**
   * Add a resource to the RedLink platform
   * @param string $resource
   * @param $data
   * @param string $dataset
   * @param bool $cleanBefore
   * @return bool|mixed
   */
  public function importResource($resource, $data, $dataset, $cleanBefore = FALSE) {
    $resourceServiceClient = $this->credentials->buildUrl($this->getResourceUri($resource, $dataset));
    $format = \EasyRdf_Format::getFormat('turtle');
    if ($cleanBefore) {
      $response = $resourceServiceClient
        ->put(NULL, array(
          "Content-Type" => "text/plain",
          "Accept" => $format->getDefaultMimeType()
        ), $data)->send();
    }
    else {
      $response = $resourceServiceClient
        ->post(NULL, array(
          "Content-Type" => "text/plain",
          "Accept" => $format->getDefaultMimeType()
        ), $data)->send();
    }

    return ($response->getStatusCode() == 200);
  }

  /**
   * Delete a resource from the RedLink Platform
   * @param $resource
   * @param $dataset
   * @return bool|mixed
   */
  public function deleteResource($resource, $dataset) {
    $resourceServiceClient = $this->credentials->buildUrl($this->getResourceUri($resource, $dataset));
    $response = $resourceServiceClient->delete()->send();
    return ($response->getStatusCode() == 200);

  }

  /**
   * Execute a SPARQL Select query
   * @param $query
   * @param string $dataset
   * @return mixed|void
   */
  public function sparqlSelect($query, $dataset = '') {
    $sparqlServiceClient = $this->credentials->buildUrl($this->getSparqlSelectUri($dataset));
    return $this->execSelect($sparqlServiceClient, $query);
  }

  private function execSelect($serviceClient, $query) {
    $format = \EasyRdf_Format::getFormat('sparql-json');
    $response = $serviceClient
      ->post(NULL, array(
        "Content-Type" => "text/plain",
        "Accept" => $format->getDefaultMimeType()
      ), $query)->send();

    if ($response->getStatusCode() == 200) {
      //todo
      $sparqlResultObj = new \EasyRdf_Sparql_Result($response->getBody(true), $format->getDefaultMimeType());
      return $response;
    }
    else {
      throw new \RuntimeException("Query failed: HTTP error code " . $response->getStatusCode());
    }

  }

  /**
   * Execute a SPARQL Update query
   * @param $query
   * @param $dataset
   * @return bool|mixed
   */
  public function sparqlUpdate($query, $dataset) {
    $sparqlServiceClient = $this->credentials->buildUrl($this->getSparqlUpdateUri($dataset));
    return $this->execUpdate($sparqlServiceClient, $query);
  }

  private function execUpdate($serviceClient, $query) {
    $response = $serviceClient
      ->post(NULL, array(
        "Content-Type" => self::$SPARQL_UPDATE_FORMAT
      ), $query)->send();
    return ($response->getStatusCode() == 200);
  }

  public function ldpath($uri, $program, $dataset = '') {
    $ldPathServiceClient = $this->credentials->buildUrl($this->getLDPathUri($uri, $dataset));
    $this->execLDPath($ldPathServiceClient, $program);


  }

  private function execLDPath($serviceClient, $program) {
    $response = $serviceClient
      ->post(NULL, array(
        "Content-Type" => "text/plain",
      ), $program)->send();

    if ($response->getStatusCode() == 200) {
      //todo
    }
    else {
      throw new \RuntimeException("Query failed: HTTP error code " . $response->getStatusCode());
    }


  }

  /**
   * A function to create the URL for ading and removing datasets
   * @param string $dataset
   * @return string returns the url for adding or removing datasets
   */
  private function getDataUri($dataset) {
    $initUrl = $this->initiateUriBuilding();
    return \http_build_url($initUrl, array("path" => self::PATH . DIRECTORY_SEPARATOR . $dataset), HTTP_URL_STRIP_AUTH | HTTP_URL_JOIN_PATH | HTTP_URL_STRIP_FRAGMENT);
  }

  /**
   * Construct the resource URL
   * @param string $resource
   * @param string $dataset
   * @return string Returns the resource URL
   */
  private function getResourceUri($resource, $dataset = '') {
    $initUrl = $this->initiateUriBuilding();
    if (!empty($dataset)) {
      return \http_build_url($initUrl, array(
        "path" => self::PATH . DIRECTORY_SEPARATOR . $dataset . DIRECTORY_SEPARATOR . self::RESOURCE,
        "query" => self::URI . "=" . $resource
      ), HTTP_URL_STRIP_AUTH | HTTP_URL_JOIN_PATH | HTTP_URL_JOIN_QUERY | HTTP_URL_STRIP_FRAGMENT);
    }
    else {
      return \http_build_url($initUrl, array(
        "path" => self::PATH . DIRECTORY_SEPARATOR . self::RESOURCE,
        "query" => self::URI . "=" . $resource
      ), HTTP_URL_STRIP_AUTH | HTTP_URL_JOIN_PATH | HTTP_URL_JOIN_QUERY | HTTP_URL_STRIP_FRAGMENT);
    }
  }

  /**
   * Construct SPARQL select service URL
   * @param string $dataset
   * @return string
   */
  private function getSparqlSelectUri($dataset = '') {
    if (!empty($dataset)) {
      return \http_build_url($this->getDataUri($dataset), array("path" => self::SPARQL . DIRECTORY_SEPARATOR . self::SELECT), HTTP_URL_STRIP_AUTH | HTTP_URL_JOIN_PATH | HTTP_URL_STRIP_FRAGMENT);
    }
    else {
      $initUrl = $this->initiateUriBuilding();
      return \http_build_url($initUrl, array("path" => self::PATH . DIRECTORY_SEPARATOR . self::SPARQL), HTTP_URL_STRIP_AUTH | HTTP_URL_JOIN_PATH | HTTP_URL_STRIP_FRAGMENT);
    }

  }

  /**
   * Construct the LDPath Service URL
   * @param string $uri
   * @param string $dataset
   * @return string Return the LDPATH service URL string
   */
  private function getLDPathUri($uri, $dataset = '') {
    $initUrl = $this->initiateUriBuilding();

    if (!empty($dataset)) {
      return \http_build_url($initUrl, array(
        "path" => self::PATH . DIRECTORY_SEPARATOR . $dataset . DIRECTORY_SEPARATOR . self::LDPATH,
        "query" => self::URI . "=" . $uri
      ), HTTP_URL_STRIP_AUTH | HTTP_URL_JOIN_PATH | HTTP_URL_JOIN_QUERY | HTTP_URL_STRIP_FRAGMENT);
    }
    else {
      return \http_build_url($initUrl, array(
        "path" => self::PATH . DIRECTORY_SEPARATOR . self::LDPATH,
        "query" => self::URI . "=" . $uri
      ), HTTP_URL_STRIP_AUTH | HTTP_URL_JOIN_PATH | HTTP_URL_JOIN_QUERY | HTTP_URL_STRIP_FRAGMENT);

    }


  }

  /**
   * Construct the SPARQL update service URL
   * @param string $dataset
   * @return string
   */
  public function getSparqlUpdateUri($dataset) {
    return \http_build_url($this->getDataUri($dataset), array("path" => self::SPARQL . DIRECTORY_SEPARATOR . self::UPDATE), HTTP_URL_STRIP_AUTH | HTTP_URL_JOIN_PATH | HTTP_URL_STRIP_FRAGMENT);
  }

}

?>
