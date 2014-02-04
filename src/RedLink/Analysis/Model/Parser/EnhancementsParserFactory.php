<?php

namespace RedLink\Analysis\Model\Parser;

/**
 * <p>EnhancementsParserFactory class</p>
 * <p>Factory to create EnhancementsParser instances</p>
 * 
 * @see \RedLink\Analysis\Model\Parser\EnhancementsParser
 * @see \RedLink\Analysis\Model\Parser\EasyRdfEnhancementsParser
 *
 * @author Antonio David Pérez Morales <aperez@zaizi.com>
 */
class EnhancementsParserFactory
{
    /**
     * <p>Create a default parser for the <code>EnhancementsParser</code></p>
     * <p>By default, the \RedLink\Analysis\Model\Parser\EasyRdfEnhancementsParser class is used</p>
     * 
     * @param String $model The graph model as String to be parsed 
     * 
     * @return an instance of the \RedLink\Analysis\Model\Parser\EnhancementsParser
     * 
     */
    public static final function createDefaultParser($model) {
        return new \RedLink\Analysis\Model\Parser\RDFStructureParser($model);
    }
}

?>
