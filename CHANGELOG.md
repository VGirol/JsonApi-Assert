# Changelog

All notable changes to `jsonapi-assert` will be documented in this file.

Updates should follow the [Keep a CHANGELOG](http://keepachangelog.com/) principles.

## 1.0.0 - 2019-03-31

- Initial release

## 1.0.1 - 2019-04-04

### Added

- Updated many unit test thanks to mutation testing

## 1.0.2 - 2019-04-08

### Added

- Linted many files
- Refactored some unit tests
- Updated the README file

## 1.1.0 - 2019-07-20

### Added

- Added Factory classes for tests

## 1.2.0 - 2019-08-17

### Removed

- Moved Factory classes into another package : VGirol/JsonApi-Faker

## 1.2.1 - 2019-09-30

### Added

- Added some failure messages
- Added new methods for testing (VGirol\JsonApiAssert\SetExceptionsTrait::setFailure and VGirol\JsonApiAssert\SetExceptionsTrait::formatAsRegex)

## 2.0.0 - 2020-01-27

### Added

- Use of vgirol/jsonapi-structure package
- Changed exception thrown by all assertion methods (\PHPUnit\Framework\AssertionFailedError)

## 2.0.1 - 2020-01-27

### Added

- Minor fix

## 2.1.0 - 2020-02-29

### Removed

- Removed testings tools (use of vgirol/phpunit-exception)

## 2.1.1 - 2020-04-12

### Added

- Updated branch alias in composer.json
