<?php
/**
 * Created by JetBrains PhpStorm.
 * User: nmeegama
 * Date: 2/3/14
 * Time: 10:58 AM
 * To change this template use File | Settings | File Templates.
 */

namespace RedLink;


interface IData {

  const URI = "uri";
  const PATH = "data";
  const RESOURCE = "resource";
  const SPARQL = "sparql";
  const SELECT = "select";
  const QUERY = "query";
  const UPDATE = "update";
  const LDPATH = "ldpath";

  /**
   * @param $data
   * @param string $format
   * @param string $dataset
   * @param bool $cleanBefore
   * @return mixed
   */
  public function importDataset($data, $format = '', $dataset = '', $cleanBefore = FALSE);

  /**
   * @param $dataset
   * @return mixed
   */
  public function exportDataset($dataset);

  /**
   * @param $dataset
   * @return mixed
   */
  public function cleanDataset($dataset);

  /**
   * @param $resource
   * @param string $dataset
   * @return mixed
   */
  public function getRescouce($resource, $dataset = '');

  /**
   * @param $resource
   * @param $data
   * @param $dataset
   * @param bool $cleanBefore
   * @return mixed
   */
  public function importResource($resource, $data, $dataset, $cleanBefore = FALSE);

  /**
   * @param $resouce
   * @param $dataset
   * @return mixed
   */
  public function deleteResource($resouce, $dataset);

  /**
   * @param $query
   * @param string $dataset
   * @return mixed
   */
  public function sparqlSelect($query, $dataset = '');

  /**
   * @param $query
   * @param $dataset
   * @return mixed
   */
  public function sparqlUpdate($query, $dataset);

  /**
   * @param $uri
   * @param $program
   * @param string $dataset
   * @return mixed
   */
  public function ldpath($uri, $program, $dataset = '');



}