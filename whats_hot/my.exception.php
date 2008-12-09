<?php
/**
 * default exception class
 *
 * This file contains the exception to be thrown at
 * default when an expected but unhandled error occurs.
 * It extends the default PHP  Exception with details.
 *
 * @author Andreas Demmer <mail@andreas-demmer.de>
 * @version 1.1.0
 * @package andreas_demmer_de
 */
 
class myException extends Exception {
    /**
     * extended information
     *
     * @var array
     */
    protected $details;
    
    /**
     * constructor
     *
     * @return void
     * @param string $errorMessage
     * @param mixed $details
     */
    public function __construct($errorMessage = 'unknown error', $details = array()) {
        $this->details = is_array($details) ? $details : array($details);
        parent::__construct($errorMessage);
    }
    
    /**
     * delivers details
     *
     * @return array
     */
    public function getDetails() {
        return $this->details;
    }
}
?>