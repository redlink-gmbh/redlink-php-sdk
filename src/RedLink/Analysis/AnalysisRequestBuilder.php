<?php

namespace RedLink\Analysis;
/**
 * 
 * <p>Analysis Request Builder</p>
 * <p>This class eases the creation of AnalysisRequest instances</p>
 * 
 * @author Antonio David Pérez Morales <aperez@zaizi.com>
 */
class AnalysisRequestBuilder
{
    private $request;
    
    /**
     * <p>Default constructor</p>
     * <p>Initializes the Analysis request object</p>
     */
    public function __construct() {
        $this->request = new \RedLink\Analysis\AnalysisRequest();
    }
    
    /**
     * <p>Returns an instance of this builder</p>
     * 
     * @return \self
     */
    public static function builder() {
        return new self();
    }
    
    /**
     * <p>Build the request</p>
     * @return \RedLink\Analysis\AnalysisRequest containing the request
     */
    public function build() {
        return $this->request;
    }
    
    /**
     * <p>Set the analysis to be used</p>
     * 
     * @param string $analysis The name of the analysis
     * @return \RedLink\Analysis\AnalysisRequestBuilder the current request builder
     */
    public function setAnalysis($analysis) {
        $this->request->setAnalysis($analysis);
        return $this;
    }
    
    /**
     * <p>Set the request content</p>
     * <p>The content can be a string containing the content or a file name</p>
     * @param mixed $content a String containing the content or a file
     * @return \RedLink\Analysis\AnalysisRequestBuilder the current request builder
     */
    public function setContent($content) {
        $this->request->setContent($content);
        if(is_file($content)) {
        $this->request->setIsContentString(false);
        }
        else {
            $this->request->setIsContentString(true);
        }
        
        return $this;
    }
    
    /**
     * <p>Set the input format</p>
     * @param String $inputFormat The input format
     * @return \RedLink\Analysis\AnalysisRequestBuilder the current request builder
     */
    public function setInputFormat($inputFormat) {
        $this->request->setInputFormat($inputFormat);
        return $this;
    }
    
    /**
     * <p>Set the output format</p>
     * 
     * @param String $outputFormat The output format
     * @return \RedLink\Analysis\AnalysisRequestBuilder the current request builder
     */
    public function setOutputFormat($outputFormat) {
        $this->request->setOutputFormat($outputFormat);
        return $this;
    }
    
    /**
     * <p>Set Request retrieve entities summaries parameter</p>
     * 
     * @param boolean $summary Request summary parameter
     * @return \RedLink\Analysis\AnalysisRequestBuilder the current request builder
     */
    public function setSummaries($summary) {
        $this->request->setSummary($summary);
        return $this;
    }
    
    /**
     * <p>Set Request retrieve entities thumbnails parameter</p>
     * 
     * @param boolean $thumbnail Request thumbnail parameter
     * @return \RedLink\Analysis\AnalysisRequestBuilder the current request builder
     */
    public function setThumbnails($thumbnail) {
        $this->request->setThumbnail($thumbnail);
        return $this;
    }

}

?>
