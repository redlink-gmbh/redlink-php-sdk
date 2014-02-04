<?php

namespace RedLink;
/**
 * <p>RedLink Analysis Service Interface</p>
 * 
 * @author Antonio David Pérez Morales <aperez@zaizi.com>
 */
interface RedLinkAnalysis {
    
    const PATH = "analysis";
    
    const ENHANCE = "enhance";
    
    const FORMAT = "format";
		
    public function enhance(\RedLink\Analysis\AnalysisRequest $request);
}

?>
