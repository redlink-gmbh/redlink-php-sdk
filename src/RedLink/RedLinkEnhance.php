<?php

namespace RedLink;
/**
 * <p>RedLink Enhance Service Interface</p>
 * 
 * @author Antonio David Pérez Morales <aperez@zaizi.com>
 */
interface RedLinkEnhance {
    
    const LIVE = "live";
		
    const LIVE_FORMAT = "format";
		
    public function enhance($content);
}

?>
