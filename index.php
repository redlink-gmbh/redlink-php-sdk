<?php

// Require Bootstrap. Initialize
require_once("src/bootstrap.php");

// Currently the DefaultCredentials parameter is not used

$key = "QVBU1skUrDBlapFOeHZZHa3uqs1fI3vwb67d6405"; //FIXME

$credentials = new RedLink\Credential\SecureCredentials($key);
$credentials->setSSLCertificates(__DIR__.DIRECTORY_SEPARATOR.'redlink-CA.pem');

$analysisClient = \RedLink\RedLinkFactory::getInstance()->createAnalysisClient($key);
$request = \RedLink\Analysis\AnalysisRequestBuilder::builder()->setAnalysis("alfresco")->setContent("Paris is the capital of France")->setOutputFormat("json")->build();
//echo $analysisClient->enhanceRaw($request);
var_dump($analysisClient->enhance($request, true));

?> 
