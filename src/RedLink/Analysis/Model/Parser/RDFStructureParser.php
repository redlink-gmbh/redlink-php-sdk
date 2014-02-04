<?php

namespace RedLink\Analysis\Model\Parser;

/**
 * <p>Class representing an EnhancementsParser</p>
 * <p>It uses EasyRdf library in order to parse the Enhancements</p>
 *
 * @author Antonio David Pérez Morales <aperez@zaizi.com>
 * 
 */
class RdfStructureParser implements \RedLink\Analysis\Model\Parser\EnhancementsParser
{

    /**
     * <p>EasyRdf_Graph containing the enhancements model</p>
     * @var EasyRdf_Graph
     */
    private $model;

    /**
     * <p>Constructor</p>
     * <p>Constructs an instance using the enhancements passed as string parameter</p>
     * 
     * @param string $rawModel the string containing the enhancements model (in RDF)
     */
    public function __construct($rawModel)
    {
        $this->model = new \EasyRdf_Graph();
        $this->model->parse($rawModel);
    }

    /**
     * <p>Constant containing the value of the Language Detection Enhancement Engine used by Stanbol</p>
     * @var string
     */
    private static $LANGUAGE_DETECTION_ENHANCEMENT_ENGINE = "org.apache.stanbol.enhancer.engines.langdetect.LanguageDetectionEnhancementEngine";

    /**
     * <p>Creates the \RedLink\Analysis\Model\Enhancements object from the given model</p>
     * 
     * @return object A \RedLink\Analysis\Model\Enhancements object instance representing the enhancements 
     */
    public function createEnhancements()
    {
        $enhancements = new \RedLink\Analysis\Model\Enhancements($this->model);
        $enhancements->setEnhancements($this->parseEnhancements($this->model));
        $enhancements->setLanguages($this->parseLanguages($this->model));
        return $enhancements;
    }

    /**
     * <p>Parses the enhancements contained in the model</p>
     * <p>Returns both TextAnnotation and EntityAnnotation enhancements</p>
     * 
     * @return array An array containing the \RedLink\Analysis\Model\Enhancement objects
     */
    public function parseEnhancements()
    {

        $result = $this->parseTextAnnotations($this->model);
        $result = array_merge($result, $this->parseEntityAnnotations($this->model));

        $modelArray = $this->model->toArray();
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
     * <p>Parse the TextAnnotations contained in the model into <code>\RedLink\Analysis\Model\TextAnnotation</code> objects</p>
     * 
     * @return array An array containing the \RedLink\Analysis\Model\TextAnnotation objects
     */
    public function parseTextAnnotations()
    {
        $enhancements = array();

        /*
         * Gets the text annotations
         */
        $textAnnotations = $this->model->resourcesMatching(\RedLink\Ontology\RDF::TYPE, array('type' => 'uri', 'value' => \RedLink\Ontology\FISE::TEXT_ANNOTATION));
        $modelArray = $this->model->toArray();

        foreach ($textAnnotations as $taResource) {
            if (isset($modelArray[$taResource->getUri()][\RedLink\Ontology\DCTerms::CREATOR]) && $modelArray[$taResource->getUri()][\RedLink\Ontology\DCTerms::CREATOR][0]['value'] == self::$LANGUAGE_DETECTION_ENHANCEMENT_ENGINE)
                continue;

            $textAnnotation = new \RedLink\Analysis\Model\TextAnnotation($taResource->getUri());
            $this->setTextAnnotationData($textAnnotation);

            $enhancements[$textAnnotation->getUri()] = $textAnnotation;
        }

        return $enhancements;
    }

    /**
     * <p>Parse the EntityAnnotations contained in the model into <code>\RedLink\Analysis\Model\EntityAnnotation</code> objects
     * 
     * @return array An array containing the \RedLink\Analysis\Model\EntityAnnotation objects
     */
    public function parseEntityAnnotations()
    {
        $enhancements = array();

        /*
         * Gets the entity annotations
         */
        $entityAnnotations = $this->model->resourcesMatching(\RedLink\Ontology\RDF::TYPE, array('type' => 'uri', 'value' => \RedLink\Ontology\FISE::ENTITY_ANNOTATION));
        foreach ($entityAnnotations as $eaResource) {

            $entityAnnotation = new \RedLink\Analysis\Model\EntityAnnotation($eaResource->getUri());
            $this->setEntityAnnotationData($entityAnnotation);

            $enhancements[$entityAnnotation->getUri()] = $entityAnnotation;
        }

        return $enhancements;
    }

    /**
     * <p>Parse the languages of the enhancements</p>
     * 
     * 
     * @return array An array containing the languages
     */
    public function parseLanguages()
    {
        $languages = array();
        $modelArray = $this->model->toArray();
        $textAnnotationsLanguage = $this->model->resourcesMatching(\RedLink\Ontology\DCTerms::TYPE, array('type' => 'uri', 'value' => \RedLink\Ontology\DCTerms::LINGUISTIC_SYSTEM));
        foreach ($textAnnotationsLanguage as $taLangResource) {
            $taProperties = $modelArray[$taLangResource->getUri()];
            $lang = isset($taProperties[\RedLink\Ontology\DCTerms::LANGUAGE][0]['value']) ? $taProperties[\RedLink\Ontology\DCTerms::LANGUAGE][0]['value'] : '';
            if (!empty($lang) && !in_array($lang, $languages))
                array_push($languages, $lang);
        }

        return $languages;
    }

    /**
     * <p>Sets the data related to a TextAnnotation</p>
     * 
     * @param \RedLink\Analysis\Model\TextAnnotation $textAnnotation The <code>\RedLink\Analysis\Model\TextAnnotation</code> to be populated
     */
    private function setTextAnnotationData(\RedLink\Analysis\Model\TextAnnotation $textAnnotation)
    {
        /*
         * Sets the common data for an enhancement 
         */
        $this->setEnhancementData($textAnnotation);

        /*
         * Convert the model to an array for ease of manipulation
         */
        $modelArray = $this->model->toArray();
        $taProperties = $modelArray[$textAnnotation->getUri()];

        $textAnnotation->setType(isset($taProperties[\RedLink\Ontology\DCTerms::TYPE][0]['value']) ? (string) $taProperties[\RedLink\Ontology\DCTerms::TYPE][0]['value'] : null);
        $textAnnotation->setStarts(isset($taProperties[\RedLink\Ontology\FISE::START][0]['value']) ? intval($taProperties[\RedLink\Ontology\FISE::START][0]['value']) : 0);
        $textAnnotation->setEnds(isset($taProperties[\RedLink\Ontology\FISE::END][0]['value']) ? intval($taProperties[\RedLink\Ontology\FISE::END][0]['value']) : 0);
        $textAnnotation->setSelectedText(isset($taProperties[\RedLink\Ontology\FISE::SELECTED_TEXT][0]['value']) ? (string) $taProperties[\RedLink\Ontology\FISE::SELECTED_TEXT][0]['value'] : null);
        $textAnnotation->setSelectionContext(isset($taProperties[\RedLink\Ontology\FISE::SELECTION_CONTEXT][0]['value']) ? (string) $taProperties[\RedLink\Ontology\FISE::SELECTION_CONTEXT][0]['value'] : null);
        $textAnnotation->setLanguage(isset($taProperties[\RedLink\Ontology\DCTerms::LANGUAGE][0]['value']) ? (string) $taProperties[\RedLink\Ontology\DCTerms::LANGUAGE][0]['value'] : null);

        if (!isset($taProperties[\RedLink\Ontology\DCTerms::LANGUAGE]) && isset($taProperties[\RedLink\Ontology\FISE::SELECTED_TEXT][0]['lang']))
            $textAnnotation->setLanguage($taProperties[\RedLink\Ontology\FISE::SELECTED_TEXT][0]['lang']);
    }

    /**
     * <p>Sets the data related to a EntityAnnotation</p>
     * 
     * @param \RedLink\Analysis\Model\EntityAnnotation $textAnnotation The <code>\RedLink\Analysis\Model\EntityAnnotation</code> to be populated
     */
    private function setEntityAnnotationData(\RedLink\Analysis\Model\EntityAnnotation $entityAnnotation)
    {
        /*
         * Sets the common data for an enhancement 
         */
        $this->setEnhancementData($entityAnnotation);

        /*
         * Convert the model to an array for ease of manipulation
         */
        $modelArray = $this->model->toArray();
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
            $entity = $this->parseEntity($entityReferenceUri);
            $entityAnnotation->setEntityReference($entity);
        }

        $entityAnnotation->setSite(isset($eaProperties[\RedLink\Ontology\EntityHub::SITE][0]['value']) ? (string) $eaProperties[\RedLink\Ontology\EntityHub::SITE][0]['value'] : null);
    }

    /**
     * <p>Sets the data related to an enhancement</p>
     * 
     * @param \RedLink\Analysis\Model\Enhancement $enhancement The <code>\RedLink\Analysis\Model\Enhancement</code> to be populated
     */
    private function setEnhancementData(\RedLink\Analysis\Model\Enhancement $enhancement)
    {
        /*
         * Convert the model to an array for ease of manipulation
         */
        $modelArray = $this->model->toArray();
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
     * @param type $entityUri the entity uri to parse
     * 
     * @return \RedLink\Vocabulary\Model\Entity the parsed entity
     */
    public function parseEntity($entityUri)
    {
        //TODO Parse the properties when \RedLink\Vocabulary\Model\Entity is designed
        $entity = new \RedLink\Vocabulary\Model\Entity($entityUri);
        $modelArray = $this->model->toArray();

        if (isset($modelArray[$entityUri])) {
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
        }

        return $entity;
    }

    /**
     * <p>Updates the languages of the enhancements getting
     * @param \RedLink\Analysis\Model\Enhancements $enhancements The \RedLink\Analysis\Model\Enhancements to update
     */
    private function updateEnhancementsLanguages(\RedLink\Analysis\Model\Enhancements $enhancements)
    {
        $modelArray = $this->model->toArray();
        $textAnnotationsLanguages = $this->model->resourcesMatching(\RedLink\Ontology\DCTerms::CREATOR, array('type' => 'literal', 'value' => self::$LANGUAGE_DETECTION_ENHANCEMENT_ENGINE));
        foreach ($textAnnotationsLanguages as $talResource) {
            $talProperties = $modelArray[$talResource->getUri()];
            $existLanguage = isset($talProperties[\RedLink\Ontology\DCTerms::LANGUAGE]);
            if ($existLanguage)
                $enhancements->addLanguage($talProperties[\RedLink\Ontology\DCTerms::LANGUAGE][0]['value']);
        }
    }

}

?>
