<?php

namespace RedLink\Enhancer\Model\Parser;

/**
 * <p>EnhancementsParserFactory class</p>
 * <p>Factory to create EnhancementsParser instances</p>
 * 
 * @see \RedLink\Enhancer\Model\Parser\EnhancementsParser
 * @see \RedLink\Enhancer\Model\Parser\EasyRdfEnhancementsParser
 *
 * @author Antonio David Pérez Morales <aperez@zaizi.com>
 */
class EnhancementsParserFactory
{
    /**
     * <p>Create a default parser for the <code>EnhancementsParser</code></p>
     * <p>By default, the \RedLink\Enhancer\Model\Parser\EasyRdfEnhancementsParser class is used</p>
     * 
     * @param String $model The graph model as String to be parsed 
     * 
     * @return an instance of the \RedLink\Enhancer\Model\Parser\EnhancementsParser
     * 
     */
    public static final function createDefaultParser($model) {
        return new \RedLink\Enhancer\Model\Parser\EasyRdfEnhancementsParser($model);
    }
}

?>
