<?php

namespace RedLink;

/**
 * <p>RedLink's user Application Status Error data</p>
 *
 * @author Antonio David Perez Morales <aperez@zaizi.com>
 */
class StatusError
{
   private $accesible;
   private $reason;
   private $message;
   private $error;
   
   public function __construct() {
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
     * <p>Returns status error reason</p>
     * 
     * @return string
     */
    public function getReason() {
        return $this->reason;
    }
    
    /**
     * <p>Sets the status error reason</p>
     * @param string $reason
     */
    public function setReason($reason) {
        $this->reason = $reason;
    }
 
    /**
     * <p>Returns status error message</p>
     * 
     * @return string
     */
    public function getMessage() {
        return $this->message;
    }
    
    /**
     * <p>Sets the status error message</p>
     * 
     * @param string $message
     */
    public function setMessage($message) {
        $this->message = $message;
    }
    
    /**
     * <p>Returns status error code</p>
     * 
     * @return int
     */
    public function getError() {
        return $this->error;
    }
    
    /**
     * <p>Sets the status error code</p>
     * 
     * @param int $error The error code
     */ 
    public function setError($error) {
        $this->error = $error;
    }
}

?>