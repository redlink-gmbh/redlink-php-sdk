<?php

namespace RedLink;
/**
 * <p>RedLink Enhance Service Interface</p>
 * 
 * @author Antonio David Pérez Morales <aperez@zaizi.com>
 */
interface RedLinkEnhance {
    
    const PATH = "analysis";
    
    const ENHANCE = "enhance";
    
    const FORMAT = "format";
		
    public function enhance($content, $analysis);
}

?>
