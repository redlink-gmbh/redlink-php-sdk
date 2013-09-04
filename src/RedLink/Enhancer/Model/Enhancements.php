<?php

namespace RedLink\Enhancer\Model;

/**
 * <p>Enhancements Class</p>
 * <p>Represents a set of enhancements as response of the Enhancer Service</p>
 *
 * @author Antonio David Pérez Morales <adperezmorales@gmail.com>
 */
class Enhancements
{

    /**
     * Map <Enhancement Type, Enhancement Object>
     * @var array
     */
    private $enhancements = array();

    /**
     * Map <URI, Entity>
     * @var array
     */
    private $entities = array();

    /**
     * List of string
     * @var array 
     */
    private $languages = array();
    
    /**
     * Enhancement Graph \EasyRdf_Graph
     */
    private $model;

    public function __construct(\EasyRdf_Graph $model)
    {
        $this->enhancements = array();
        $this->entities = array();
        $this->model = $model;
    }

    /**
     * <p>Gets the collection of enhancements</p>
     * 
     * @return array Containing the \RedLink\Enhancer\Model\Enhancement objects
     */
    public function getEnhancements()
    {
        $result = array();
        foreach ($this->enhancements as $enhancementType => $values) {
            $result = array_merge($result, $values);
        }

        return $result;
    }

    /**
     * <p>Adds the enhancements passed as parameter to the collection of enhancements</p>
     * 
     * @param array $enhancements containing the \RedLink\Enhancer\Model\Enhancement objects
     */
    public function setEnhancements($enhancements)
    {
        foreach ($enhancements as $enhancement) {
            $this->addEnhancement($enhancement);
        }
    }

    /**
     * <p>Adds a specific enhancement to the collection of enhancements</p>
     * @param \RedLink\Enhancer\Model\Enhancement $enhancement The enhancement to add
     */
    public function addEnhancement(\RedLink\Enhancer\Model\Enhancement $enhancement)
    {

        if (!isset($this->enhancements[\RedLink\Util\ClassHelper::getClassName($enhancement)]))
            $this->enhancements[\RedLink\Util\ClassHelper::getClassName($enhancement)] = array();

        $this->enhancements[\RedLink\Util\ClassHelper::getClassName($enhancement)][$enhancement->getUri()] = $enhancement;

        if ($enhancement instanceof \RedLink\Enhancer\Model\EntityAnnotation) {
            $this->entities[$enhancement->getEntityReference()->getUri()] = $enhancement->getEntityReference();
        }
    }

    /**
     * <p>Gets the enhancements which are TextAnnotations</p>
     * 
     * @return array An array containing the TextAnnotations
     */
    public function getTextAnnotations()
    {
        return $this->enhancements['TextAnnotation'];
    }

    /**
     * <p>Gets the enhancements which are EntityAnnotations</p>
     * 
     * @return array An array containing the EntityAnnotations
     */
    public function getEntityAnnotations()
    {
        return $this->enhancements['EntityAnnotation'];
    }

    /**
     * <p>Return the entities contained in the Enhancements</p>
     * 
     * @return array containing the entities
     */
    public function getEntities()
    {
        return array_values($this->entities);
    }

    /**
     * <p>Gets the entity identified by the given uri</p>
     * @param string $uri The URI of the entity
     * @return \RedLink\Vocabulary\Model\Entity The searched entity or null if no entity is found
     */
    public function getEntity($uri)
    {
        return $this->entities[$uri];
    }

    /**
     * <p>Sets the languages of the Enhancements</p>
     * 
     * @param array $languages The array of string containing the languages
     */
    public function setLanguages($languages) {
        $this->languages = $languages;
    }
    
    /**
     * <p>Gets the languages</p>
     * @return array Containing the languages
     */
    public function getLanguages() {
        return $this->languages;
    }
    
    /**
     * <p>Adds a language to the Enhancements languages</p>
     * @param String $lang The language to add
     */
    public function addLanguage($lang) {
        if(is_string($lang) && !in_array($lang, $this->languages))
            array_push($this->languages, $lang);
    }
    
    /**
     * <p>Gets the entities associated to EntityAnnotations which 
     * have a confidence value greater than the given one</p>
     * 
     * @param float $confidenceValue The confidence value to use
     */
    public function getEntitiesByConfidenceValue($confidenceValue)
    {
     $entityAnnotations = $this->getEntityAnnotationsByConfidenceValue($confidenceValue);
     $result = array_map(function(\RedLink\Enhancer\Model\EntityAnnotation $entityAnnotation) {
         return $entityAnnotation->getEntityReference();
     }, $entityAnnotations);
     
     return $result;
    }

    /**
     * <p>Gets the TextAnnotations which 
     * have a confidence value greater than the given one</p>
     * 
     * @param float $confidenceValue The confidence value to use
     */
    public function getTextAnnotationsByConfidenceValue($confidenceValue)
    {
        return $this->getEnhancementsByConfidenceValue($this->getTextAnnotations(), $confidenceValue);
    }

    /**
     * <p>Gets the EntityAnnotations which 
     * have a confidence value greater than the given one</p>
     * 
     * @param float $confidenceValue The confidence value to use
     */
    public function getEntityAnnotationsByConfidenceValue($confidenceValue)
    {
        return $this->getEnhancementsByConfidenceValue($this->getEntityAnnotations(), $confidenceValue);
    }
    
    /**
     * <p>Gets the \RedLink\Enhancer\Model\Enhancement which 
     * have a confidence value greater than the given one and sorted by confidence value</p>
     * 
     * @param array $enhancements The array of <code>\RedLink\Enhancer\Model\Enhancement</code> instances
     * @param float $confidenceValue The confidence value to use
     */
    private function getEnhancementsByConfidenceValue($enhancements, $confidenceValue) {
        $result = array_filter($enhancements, function(\RedLink\Enhancer\Model\Enhancement $enhancement) use ($confidenceValue) {
                    return $enhancement->getConfidence() >= $confidenceValue;
                });

        // Order by confidence value
        usort($result, function(\RedLink\Enhancer\Model\Enhancement $o1, \RedLink\Enhancer\Model\Enhancement $o2) {
                    return ($o1->getConfidence() == $o2->getConfidence()) ? 0 : ($o1->getConfidence() > $o2->getConfidence() ? -1 : 1);
                });
                
        return $result;
    }
    
    /**
     * <p>Gets the best EntityAnnotation for each TextAnnotation</p>
     * <p>Returns an array which every element has two fields: The entity annotation and the text annotation</p>
     * <p>Example result:
     *    <pre>
     *      array(
     *          [0] => array('TextAnnotation' => TextAnnotation Object,
     *                       'EntityAnnotation' => EntityAnnotation Object),
     *          ...
     * 
     *          [N] => array('TextAnnotation' => TextAnnotation Object,
     *                       'EntityAnnotation' => EntityAnnotation Object),
     *      );
     * </p>
     */
    public function getBestAnnotations() {
        $hashTE = array();
        $entityAnnotations = $this->getEntityAnnotations();
        foreach($entityAnnotations as $key => $entityAnnotation) {
            if($entityAnnotation->getRelations() != null) {
                foreach($entityAnnotation->getRelations() as $key => $relation)
                {
                    if ($relation instanceof \RedLink\Enhancer\Model\TextAnnotation)
                    {
                        $hashTE[$relation->getUri()][] = $entityAnnotation;
                    }
                }
            }
        }
        
        $result = array();
        $textAnnotations = $this->getTextAnnotations();
        foreach($hashTE as $taURI => $entityAnnotations) {
            usort($entityAnnotations, function(\RedLink\Enhancer\Model\Enhancement $o1, \RedLink\Enhancer\Model\Enhancement $o2) {
                    return ($o1->getConfidence() == $o2->getConfidence()) ? 0 : ($o1->getConfidence() > $o2->getConfidence() ? -1 : 1);
            });

            if(count($entityAnnotations > 0)) {
                $textAnnotation = $textAnnotations[$taURI];
                $result[] = array('TextAnnotation' => $textAnnotation,
                              'EntityAnnotation' => $entityAnnotations[0]
                            );
            }
        }
        
        return $result;
    }
    
    /**
     * <p>Gets the raw Enhancements model</p>
     * @return \EasyRdf_Graph The raw model
     */
    public function getModel() {
        return $this->model;
    }

}

?>
