<?php

namespace RedLink\Enhancer\Model;

/**
 * <p>Helper class to deal with the Enhancements graph model</p>
 *
 * @author Antonio David Pérez Morales <adperezmorales@gmail.com>
 */
class EnhancementsParser
{

    /**
     * <p>Constant containing the value of the Language Detection Enhancement Engine used by Stanbol</p>
     * @var string
     */
    private static $LANGUAGE_DETECTION_ENHANCEMENT_ENGINE = "org.apache.stanbol.enhancer.engines.langdetect.LanguageDetectionEnhancementEngine";

    /**
     * <p>Creates the \RedLink\Enhancer\Model\Enhancements object from the given model</p>
     * 
     * @param \EasyRdf_Graph $model The model containing the resources returned by the Enhancer Service
     * @return object A \RedLink\Enhancer\Model\Enhancements object instance representing the enhancements 
     */
    public static function createEnhancements(\EasyRdf_Graph $model)
    {
        $enhancements = new \RedLink\Enhancer\Model\Enhancements($model);
        $enhancements->setEnhancements(self::parseEnhancements($model));
        self::updateEnhancementsLanguages($enhancements, $model);
        return $enhancements;
    }

    /**
     * <p>Parses the enhancements contained in the given model</p>
     * <p>Returns both TextAnnotation and EntityAnnotation enhancements</p>
     * @param \EasyRdf_Graph $model The model containing the resources returned by the Enhancer Service
     * @return array An array containing the \RedLink\Enhancer\Model\Enhancement objects
     */
    public static function parseEnhancements(\EasyRdf_Graph $model)
    {

        $result = self::parseTextAnnotations($model);
        $result = array_merge($result, self::parseEntityAnnotations($model));

        $modelArray = $model->toArray();
        foreach ($result as $enhancementUri => $enhancementInstance) {
            $enhancementProperties = $modelArray[$enhancementUri];
            if (isset($enhancementProperties[\RedLink\Ontology\DCTerms::RELATION])) {
                $relations = array();
                foreach ($enhancementProperties[\RedLink\Ontology\DCTerms::RELATION] as $relationTypedValue) {
                    if (isset($result[$relationTypedValue['value']]))
                        $relations[$relationTypedValue['value']] = $result[$relationTypedValue['value']];
                }

                $enhancementInstance->setRelations($relations);
            }
        }

        return $result;
    }

    /**
     * <p>Parse the TextAnnotations contained in the model into <code>\RedLink\Enhancer\Model\TextAnnotation</code> objects</p>
     * 
     * @param \EasyRdf_Graph $model The model containing the resources returned by the Enhancer Service
     * @return array An array containing the \RedLink\Enhancer\Model\TextAnnotation objects
     */
    public static function parseTextAnnotations(\EasyRdf_Graph $model)
    {
        $enhancements = array();

        /*
         * Gets the text annotations
         */
        $textAnnotations = $model->resourcesMatching(\RedLink\Ontology\RDF::TYPE, array('type' => 'uri', 'value' => \RedLink\Ontology\FISE::TEXT_ANNOTATION));
        $modelArray = $model->toArray();

        foreach ($textAnnotations as $taResource) {
            if (isset($modelArray[$taResource->getUri()][\RedLink\Ontology\DCTerms::CREATOR]) && $modelArray[$taResource->getUri()][\RedLink\Ontology\DCTerms::CREATOR][0]['value'] == self::$LANGUAGE_DETECTION_ENHANCEMENT_ENGINE)
                continue;

            $textAnnotation = new \RedLink\Enhancer\Model\TextAnnotation($taResource->getUri());
            self::setTextAnnotationData($textAnnotation, $model);

            $enhancements[$textAnnotation->getUri()] = $textAnnotation;
        }

        return $enhancements;
    }

    /**
     * <p>Parse the EntityAnnotations contained in the model into <code>\RedLink\Enhancer\Model\EntityAnnotation</code> objects
     * 
     * @param \EasyRdf_Graph $model The model containing the resources returned by the Enhancer Service
     * @return array An array containing the \RedLink\Enhancer\Model\EntityAnnotation objects
     */
    public static function parseEntityAnnotations(\EasyRdf_Graph $model)
    {
        $enhancements = array();

        /*
         * Gets the entity annotations
         */
        $entityAnnotations = $model->resourcesMatching(\RedLink\Ontology\RDF::TYPE, array('type' => 'uri', 'value' => \RedLink\Ontology\FISE::ENTITY_ANNOTATION));
        foreach ($entityAnnotations as $eaResource) {

            $entityAnnotation = new \RedLink\Enhancer\Model\EntityAnnotation($eaResource->getUri());
            self::setEntityAnnotationData($entityAnnotation, $model);

            $enhancements[$entityAnnotation->getUri()] = $entityAnnotation;
        }

        return $enhancements;
    }

    /**
     * <p>Sets the data related to a TextAnnotation</p>
     * @param \RedLink\Enhancer\Model\TextAnnotation $textAnnotation The <code>\RedLink\Enhancer\Model\TextAnnotation</code> to be populated
     * @param \EasyRdf_Graph $model The original model containing all the enhancements and other information returned by the Enhancement Service
     */
    private static function setTextAnnotationData(\RedLink\Enhancer\Model\TextAnnotation $textAnnotation, \EasyRdf_Graph $model)
    {
        /*
         * Sets the common data for an enhancement 
         */
        self::setEnhancementData($textAnnotation, $model);

        /*
         * Convert the model to an array for ease of manipulation
         */
        $modelArray = $model->toArray();
        $taProperties = $modelArray[$textAnnotation->getUri()];

        $textAnnotation->setType(isset($taProperties[\RedLink\Ontology\DCTerms::TYPE][0]['value']) ? (string) $taProperties[\RedLink\Ontology\DCTerms::TYPE][0]['value'] : null);
        $textAnnotation->setStarts(isset($taProperties[\RedLink\Ontology\FISE::START][0]['value']) ? intval($taProperties[\RedLink\Ontology\FISE::START][0]['value']) : 0);
        $textAnnotation->setEnds(isset($taProperties[\RedLink\Ontology\FISE::END][0]['value']) ? intval($taProperties[\RedLink\Ontology\FISE::END][0]['value']) : 0);
        $textAnnotation->setSelectedText(isset($taProperties[\RedLink\Ontology\FISE::SELECTED_TEXT][0]['value']) ? (string) $taProperties[\RedLink\Ontology\FISE::SELECTED_TEXT][0]['value'] : null);
        $textAnnotation->setSelectionContext(isset($taProperties[\RedLink\Ontology\FISE::SELECTION_CONTEXT][0]['value']) ? (string) $taProperties[\RedLink\Ontology\FISE::SELECTION_CONTEXT][0]['value'] : null);
        $textAnnotation->setLanguage(isset($taProperties[\RedLink\Ontology\DCTerms::LANGUAGE][0]['value']) ? (string) $taProperties[\RedLink\Ontology\DCTerms::LANGUAGE][0]['value'] : null);
        
        if(!isset($taProperties[\RedLink\Ontology\DCTerms::LANGUAGE]) && isset($taProperties[\RedLink\Ontology\FISE::SELECTED_TEXT][0]['lang']))
                $textAnnotation->setLanguage ($taProperties[\RedLink\Ontology\FISE::SELECTED_TEXT][0]['lang']);
    }

    /**
     * <p>Sets the data related to a EntityAnnotation</p>
     * @param \RedLink\Enhancer\Model\EntityAnnotation $textAnnotation The <code>\RedLink\Enhancer\Model\EntityAnnotation</code> to be populated
     * @param \EasyRdf_Graph $model The original model containing all the enhancements and other information returned by the Enhancement Service
     */
    private static function setEntityAnnotationData(\RedLink\Enhancer\Model\EntityAnnotation $entityAnnotation, \EasyRdf_Graph $model)
    {
        /*
         * Sets the common data for an enhancement 
         */
        self::setEnhancementData($entityAnnotation, $model);

        /*
         * Convert the model to an array for ease of manipulation
         */
        $modelArray = $model->toArray();
        $eaProperties = $modelArray[$entityAnnotation->getUri()];

        $entityAnnotation->setEntityLabel(isset($eaProperties[\RedLink\Ontology\FISE::ENTITY_LABEL][0]['value']) ? (string) $eaProperties[\RedLink\Ontology\FISE::ENTITY_LABEL][0]['value'] : null);
        $entityAnnotation->setEntityReference(isset($eaProperties[\RedLink\Ontology\FISE::ENTITY_LABEL][0]['value']) ? (string) $eaProperties[\RedLink\Ontology\FISE::ENTITY_LABEL][0]['value'] : null);

        if (isset($eaProperties[\RedLink\Ontology\FISE::ENTITY_TYPE])) {
            $entityTypes = array();
            foreach ($eaProperties[\RedLink\Ontology\FISE::ENTITY_TYPE] as $typedValue) {
                array_push($entityTypes, $typedValue['value']);
            }
            
            $entityAnnotation->setEntityTypes($entityTypes);
        }

        if (isset($eaProperties[\RedLink\Ontology\FISE::ENTITY_REFERENCE])) {
            $entityReferenceUri = $eaProperties[\RedLink\Ontology\FISE::ENTITY_REFERENCE][0]['value'];
            $entity = self::parseEntity($model, $entityReferenceUri);
            $entityAnnotation->setEntityReference($entity);
        }

        $entityAnnotation->setSite(isset($eaProperties[\RedLink\Ontology\EntityHub::SITE][0]['value']) ? (string) $eaProperties[\RedLink\Ontology\EntityHub::SITE][0]['value'] : null);
    }

    /**
     * <p>Sets the data related to an enhancement</p>
     * @param \RedLink\Enhancer\Model\Enhancement $enhancement The <code>\RedLink\Enhancer\Model\Enhancement</code> to be populated
     * @param \EasyRdf_Graph $model The original model containing all the enhancements and other information returned by the Enhancement Service
     */
    private static function setEnhancementData(\RedLink\Enhancer\Model\Enhancement $enhancement, \EasyRdf_Graph $model)
    {
        /*
         * Convert the model to an array for ease of manipulation
         */
        $modelArray = $model->toArray();
        $enProperties = $modelArray[$enhancement->getUri()];

        $enhancement->setConfidence(isset($enProperties[\RedLink\Ontology\FISE::CONFIDENCE][0]['value']) ? intval($enProperties[\RedLink\Ontology\FISE::CONFIDENCE][0]['value']) : 0);
        $enhancement->setCreated(isset($enProperties[\RedLink\Ontology\DCTerms::CREATED][0]['value']) ? (string) ($enProperties[\RedLink\Ontology\DCTerms::CREATED][0]['value']) : null);
        $enhancement->setCreator(isset($enProperties[\RedLink\Ontology\DCTerms::CREATOR][0]['value']) ? (string) ($enProperties[\RedLink\Ontology\DCTerms::CREATOR][0]['value']) : null);
        $enhancement->setLanguage(isset($enProperties[\RedLink\Ontology\DCTerms::LANGUAGE][0]['value']) ? (string) ($enProperties[\RedLink\Ontology\DCTerms::LANGUAGE][0]['value']) : null);
        $enhancement->setExtractedFrom(isset($enProperties[\RedLink\Ontology\FISE::EXTRACTED_FROM][0]['value']) ? (string) ($enProperties[\RedLink\Ontology\FISE::EXTRACTED_FROM][0]['value']) : null);
    }

    /**
     * <p>Parse an entity identified by the entity uri into \RedLink\Vocabulary\Model\Entity</p>
     * 
     * @param \EasyRdf_Graph $model The original model containing all the enhancements and entities returned by the Enhancement Service
     * @param type $entityUri the entity uri to parse
     * 
     * @return \RedLink\Vocabulary\Model\Entity the parsed entity
     */
    private static function parseEntity(\EasyRdf_Graph $model, $entityUri)
    {
        //TODO Parse the properties when \RedLink\Vocabulary\Model\Entity is designed
        $entity = new \RedLink\Vocabulary\Model\Entity($entityUri);
        $modelArray = $model->toArray();

        $entityProperties = $modelArray[$entityUri];
        foreach ($entityProperties as $property => $arrayValues) {
            foreach ($arrayValues as $propertyArrayValue) {
                /*
                 * $propertyArrayValue is in the form of:
                 *  array(
                 *      'type' => uri|literal,
                 *      'value' => THE_VALUE,
                 *      'lang' => LANGUAGE (optional),
                 *      'datatype' => DATATYPE (optional)
                 *       );
                 *
                 */
                $lang = isset($propertyArrayValue['lang']) ? $propertyArrayValue['lang'] : null;
                $entity->addPropertyValue($property, $propertyArrayValue['value'], $lang);
            }
        }

        return $entity;
    }

    /**
     * <p>Updates the languages of the enhancements getting
     * @param \RedLink\Enhancer\Model\Enhancements $enhancements The \RedLink\Enhancer\Model\Enhancements to update
     * @param \EasyRdf_Graph $model The original model containing all the enhancements returned by the Enhancement Service
     */
    private static function updateEnhancementsLanguages(\RedLink\Enhancer\Model\Enhancements $enhancements, \EasyRdf_Graph $model)
    {
        $modelArray = $model->toArray();
        $textAnnotationsLanguages = $model->resourcesMatching(\RedLink\Ontology\DCTerms::CREATOR, array('type' => 'literal', 'value' => self::$LANGUAGE_DETECTION_ENHANCEMENT_ENGINE));
        foreach ($textAnnotationsLanguages as $talResource) {
            $talProperties = $modelArray[$talResource->getUri()];
            $existLanguage = isset($talProperties[\RedLink\Ontology\DCTerms::LANGUAGE]);
            if ($existLanguage)
                $enhancements->addLanguage($talProperties[\RedLink\Ontology\DCTerms::LANGUAGE][0]['value']);
        }
    }

}

?>
