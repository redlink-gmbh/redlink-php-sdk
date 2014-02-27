<?php

// Require Bootstrap. Initialize
require_once("src/bootstrap.php");

// Currently the DefaultCredentials parameter is not used

$credentials = new RedLink\Credential\SecureCredentials("8KcDAIk7kfN2JrJfDwxnjOPp4KdL7u0gefac4544");
$credentials->setSSLCertificates(__DIR__.DIRECTORY_SEPARATOR.'redlink-CA.pem');

$analysisClient = \RedLink\RedLinkFactory::getInstance()->createAnalysisClient("8KcDAIk7kfN2JrJfDwxnjOPp4KdL7u0gefac4544");
$request = \RedLink\Analysis\AnalysisRequestBuilder::builder()->setAnalysis("alfresco")->setContent("Paris is the capital of France")->setOutputFormat("json")->build();
//echo $analysisClient->enhanceRaw($request);
var_dump($analysisClient->enhance($request, true));

?> 
