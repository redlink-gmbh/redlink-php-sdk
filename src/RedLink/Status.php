<?php

namespace RedLink;
/**
 * 
 * <p>RedLink's user Application Status data</p>
 * 
 * @author Antonio David Pérez Morales <aperez@zaizi.com>
 *
 */
class Status
{
    private $owner;
    private $accesible;
    private $bytes;
    private $requests;
    private $limit;
    private $seconds;
    private $datasets;
    private $analyses;
    
    public function __construct(){}
    
    /**
     * <p>Returns the owner id</p>
     *
     * @return owner id
     */
    public function getOwner() {
        return $this->owner;
    }
    
    /**
     * <p>Sets the owner id</p>
     * 
     * @param $owner the owner
     */
    public function setOwner($owner) {
        $this->owner = $owner;
    }
    
    /**
     * <p>Returns true if the Application is accesible</p>
     * 
     * @return boolean
     */
    public function isAccesible() {
        return $this->accesible;
    }
    
    /**
     * <p>Sets whether the Application is accesible</p>
     * 
     * @param boolean $accesible
     */
    public function setAccesible($accesible) {
        $this->accesible = $accesible;
    }
    
    /**
     * <p>Returns the number of bytes consumed by the Application</p>
     * 
     * @return int
     */
    public function getBytes() {
        return $this->bytes;
    }
    
    /**
     * <p>Sets the number of bytes consumed by the Application</p>
     * @param int $bytes
     */
    public function setBytes($bytes) {
        $this->bytes = $bytes;
    }
 
    /**
     * <p>Returns the number of request attended by the Application</p>
     * 
     * @return int
     */
    public function getRequests() {
        return $this->requests;
    }
    
    /**
     * <p>Sets the number of request</p>
     * 
     * @param int $requests
     */
    public function setRequests($requests) {
        $this->requests = $requests;
    }
    
    /**
     * <p>Returns the limit of the Application</p>
     * 
     * @return int
     */
    public function getLimit() {
        return $this->limit;
    }
    
    /**
     * <p>Sets the limit of the Application</p>
     * 
     * @param mixed $limit The limit or "unlimited" if there is no limit. -1 means no limit
     */
    public function setLimit($limit) {
        if(is_string($limit)) {
            if($limit == "unlimited") {
                $this->limit = -1;
            }
            else {
                $this->limit = 0;
            }
        }
        else {
            $this->limit = $limit;
        }
    }
    
    /**
     * <p>Returns the number of seconds that the application has been active</p>
     * 
     * @return int
     */
    public function getSeconds() {
        return $this->seconds;
    }
    
    /**
     * <p>Sets the number of seconds that the application has been active</p>
     * 
     * @param int $seconds
     */
    public function setSeconds($seconds) {
        $this->seconds = $seconds;
    }
    
    /**
     * <p>Datasets bound</p>
     * 
     * @return array
     */
    public function getDatasets() {
        return $this->datasets;
    }
    
    /**
     * <p>Set datasets</p>
     * 
     * @param array $datasets
     */
    public function setDatasets($datasets) {
        $this->datasets = $datasets;
    }
    
    /**
     * <p>Analyses bound</p>
     * 
     * @return array
     */
    public function getAnalyses() {
        return $this->analyses;
    }
    
    /**
     * <p>Set analyses</p>
     * 
     * @param array $analyses
     */
    public function setAnalyses($analyses) {
        $this->analyses = $analyses;
    }
}

?>   
   