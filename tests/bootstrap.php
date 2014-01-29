<?php

/**
 * Bootstrap for testing:
 *  - Loads the src boostrap
 *  - Define Tests root directory
 */
require(realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'bootstrap.php'));
define('REDLINK_TEST_ROOT_PATH', dirname(__FILE__));

?>
