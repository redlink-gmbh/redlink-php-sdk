<?php

namespace RedLink;

/**
 * 
 * RedLink SDK Factory
 * 
 * @author Antonio David Pérez Morales <aperez@zaizi.com>
 *
 */
class RedLinkFactory {
	
        /**
         * <p>Creates a new instance of Enhancer Service</p>
         * 
         * @param \RedLink\Credentials $credentials the <code>\RedLink\Credentials</code> object containing the credentials to use
         * @return \RedLink\Enhancer\RedLinkEnhanceImpl an instance of <code>\RedLink\Enhancer\RedLinkEnhanceImpl</code> 
         */
	public static function createEnhanceClient(\RedLink\Credentials $credentials) {
		return new \RedLink\Enhancer\RedLinkEnhanceImpl($credentials);
	}
	
}

?>
