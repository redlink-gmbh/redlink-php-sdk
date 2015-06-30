# Redlink PHP SDK Development

## Unit Tests

To properly run the test suite there are few requisites need to be in place:

* You would need to [install Composer](https://getcomposer.org/download/) and install required dependencies: `php composer.phar install`
* Run the test suite: `phpunit`

## Packaging

To package the SDK you need to:

* Install [phar-composer](https://github.com/clue/phar-composer)
* Build the PHAR package: `php phar-composer.phar build`
* And you'll get the package in thw working directory

