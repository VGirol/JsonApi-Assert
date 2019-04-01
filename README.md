# JsonApi-Assert

[![Build Status](https://travis-ci.org/VGirol/JsonApi-Assert.svg?branch=master)](https://travis-ci.org/VGirol/JsonApi-Assert)
[![Code Coverage](https://scrutinizer-ci.com/g/VGirol/JsonApi-Assert/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/VGirol/JsonApi-Assert/?branch=master)
[![Infection MSI](https://badge.stryker-mutator.io/github.com/VGirol/JsonApi-Assert/master)](https://infection.github.io)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/VGirol/JsonApi-Assert/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/VGirol/JsonApi-Assert/?branch=master)

This package provides a lot of assertions for testing documents using the [JSON:API specification](https://jsonapi.org/).

## Technologies

- PHP 7.2+
- PHPUnit 8.0+
  
## Installation

To install through composer, simply put the following in your `composer.json` file:

```json
{
    "require-dev": {
        "vgirol/jsonapi-assert": "dev-master"
    }
}
```

And then run `composer install` from the terminal.

### Quick Installation

Above installation can also be simplified by using the following command:

```sh
composer require vgirol/jsonapi-assert
```

## Usage

You can use these assertions in your classes directly as a static call.

```php
use PHPUnit\Framework\TestCase;
use VGirol\JsonApiAssert\Assert as JsonApiAssert;

class MyTest extends TestCase
{
    /**
     * @test
     */
    public function my_first_test()
    {
        $json = [
            'meta' => [
                'key' => 'value'
            ],
            'jsonapi' => [
                'version' => '1.0'
            ]
        ];

        JsonApiAssert::assertHasValidStructure($json);
    }
}
```

```php
use PHPUnit\Framework\TestCase;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiAssert\Assert as JsonApiAssert;

class MyTest extends TestCase
{
    /**
     * @test
     */
    public function how_to_assert_that_a_test_failed()
    {
        $json = [
            'errors' => [
                'error' => 'not an array of error objects'
            ]
        ];
        $failureMsg = Messages::ERRORS_OBJECT_NOT_ARRAY;

        $fn = function ($json) {
            JsonApiAssert::assertHasValidStructure($json);
        };

        JsonApiAssert::assertTestFail($fn, $failureMsg, $json);
    }
}
```

## Assertions (`VGirol\JsonApiAssert\Assert`)

- assertContainsAtLeastOneMember($expected, $actual, $message = '')
- assertContainsOnlyAllowedMembers($expected, $actual, $message = '')
- assertFieldHasNoForbiddenMemberName($field)
- assertHasAttributes($json)
- assertHasData($json)
- assertHasErrors($json)
- assertHasIncluded($json)
- assertHasLinks($json)
- assertHasMember($key, $json)
- assertHasMembers(array $keys, $json)
- assertHasMeta($json)
- assertHasOnlyMembers(array $keys, $json)
- assertHasRelationships($json)
- assertHasValidFields($resource)
- assertHasValidStructure($json)
- assertHasValidTopLevelMembers($json)
- assertIsArrayOfObjects($data, $message = '')
- assertIsNotArrayOfObjects($data, $message = '')
- assertIsNotForbiddenFieldName($name)
- assertIsNotForbiddenMemberName($name)
- assertIsValidAttributesObject($attributes)
- assertIsValidErrorLinksObject($links)
- assertIsValidErrorObject($error)
- assertIsValidErrorsObject($errors)
- assertIsValidErrorSourceObject($source)
- assertIsValidIncludedCollection($included, $data)
- assertIsValidJsonapiObject($jsonapi)
- assertIsValidLinkObject($link)
- assertIsValidLinksObject($links, $allowedMembers)
- assertIsValidMemberName($name, $strict = false)
- assertIsValidMetaObject($meta)
- assertIsValidPrimaryData($data)
- assertIsValidRelationshipLinksObject($data, $withPagination)
- assertIsValidRelationshipObject($relationship)
- assertIsValidRelationshipsObject($relationships)
- assertIsValidResourceCollection($list, $checkType)
- assertIsValidResourceIdentifierObject($resource)
- assertIsValidResourceLinkage($data)
- assertIsValidResourceLinksObject($data)
- assertIsValidResourceObject($resource)
- assertIsValidSingleResource($resource)
- assertIsValidTopLevelLinksMember($links)
- assertNotHasMember($key, $json)
- assertResourceIdMember($resource)
- assertResourceObjectHasValidTopLevelStructure($resource)
- assertResourceTypeMember($resource)
- assertTestFail($fn, $expectedFailureMessage)

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

```sh
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

[Vincent Girol](mailto:vincent@girol.fr)

## License

This project is licensed under the [MIT](https://choosealicense.com/licenses/mit/) License.
