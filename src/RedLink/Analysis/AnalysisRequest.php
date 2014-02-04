<?php
namespace RedLink\Analysis;

/**
 * 
 * <p>Analysis Request</p>
 * <p>Represents a request to the analysis service</p>
 * 
 * @author Antonio David Pérez Morales <aperez@zaizi.com>
 */
class AnalysisRequest {
    
    private $analysis;
    private $content;
    private $inputFormat;
    private $isContentString;
    private $outputFormat;
    private $summary;
    private $thumbnail;
    
    public function __construct(){
        
    }
    
    public function getAnalysis()
    {
        return $this->analysis;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getInputFormat()
    {
        return $this->inputFormat;
    }

    public function isContentString()
    {
        return $this->isContentString;
    }

    public function getOutputFormat()
    {
        return $this->outputFormat;
    }

    public function getSummary()
    {
        return $this->summary;
    }

    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    public function setAnalysis($analysis)
    {
        $this->analysis = $analysis;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function setInputFormat($inputFormat)
    {
        $this->inputFormat = $inputFormat;
    }

    public function setIsContentString($isContentString)
    {
        $this->isContentString = $isContentString;
    }

    public function setOutputFormat($outputFormat)
    {
        $this->outputFormat = $outputFormat;
    }

    public function setSummary($summary)
    {
        $this->summary = $summary;
    }

    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;
    }


    
}

?>
