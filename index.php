<?php

// Require Bootstrap. Initialize
require_once("src/bootstrap.php");

// Currently the DefaultCredentials parameter is not used

$credentials = new RedLink\Credential\DefaultCredentials("123");
$enhancer = new RedLink\Enhancer\RedLinkEnhanceImpl($credentials);

$enhancements = $enhancer->enhance("Paris is the capital of France");

/* Get all enhancements \RedLink\Enhancer\Model\Enhancement
 * and print URI and Confidence Value
 */
echo "------------ ENHANCEMENTS ------------".PHP_EOL;
foreach($enhancements->getEnhancements() as $enhancement) {
    echo "URI: ".$enhancement->getUri()."--> confidence: ".$enhancement->getConfidence().PHP_EOL;
}

// Get Text Annotations. Array of \RedLink\Enhancer\Model\TextAnnotation object
$textAnnotations = $enhancements->getTextAnnotations();

// Get Entity Annotations. Array of \RedLink\Enhancer\Model\EntityAnnotation object
$entityAnnotations = $enhancements->getEntityAnnotations();


// Filter TextAnnotations by confidence value greater than 0.5
echo PHP_EOL."------------ FILTERING TEXT ANNOTATIONS BY CONFIDENCE VALUE GREATER THAN 0.5 ------------".PHP_EOL;
$filteredTAs = $enhancements->getTextAnnotationsByConfidenceValue(0.5);
foreach($filteredTAs as $ta) {
    echo "URI: ".$ta->getUri()."--> confidence: ".$ta->getConfidence().PHP_EOL;
}

// Filter EntityAnnotations by confidence value greater than 0.5
echo PHP_EOL."------------ FILTERING ENTITY ANNOTATIONS BY CONFIDENCE VALUE GREATER THAN 0.5 ------------".PHP_EOL;
$filteredEAs = $enhancements->getEntityAnnotationsByConfidenceValue(0.5);
foreach($filteredEAs as $ea) {
    echo "URI: ".$ea->getUri()."--> confidence: ".$ea->getConfidence().PHP_EOL;
}

?> 
