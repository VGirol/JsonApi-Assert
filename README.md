# JsonApi-Assert

[![Build Status](https://travis-ci.org/VGirol/JsonApi-Assert.svg?branch=master)](https://travis-ci.org/VGirol/JsonApi-Assert)
[![Code Coverage](https://scrutinizer-ci.com/g/VGirol/JsonApi-Assert/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/VGirol/JsonApi-Assert/?branch=master)
[![Infection MSI](https://badge.stryker-mutator.io/github.com/VGirol/JsonApi-Assert/master)](https://infection.github.io)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/VGirol/JsonApi-Assert/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/VGirol/JsonApi-Assert/?branch=master)

This package provides a set of assertions to test documents using the [JSON:API specification](https://jsonapi.org/).

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
use VGirol\JsonApiAssert\Assert as JsonApiAssert;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiAssert\SetExceptionsTrait;

class MyTest extends TestCase
{
    use SetExceptionsTrait;

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
        $failureMessage = Messages::ERRORS_OBJECT_NOT_ARRAY;

        $this->setFailureException($failureMessage);

        JsonApiAssert::assertHasValidStructure($json);
    }
}
```

## Assertions (`VGirol\JsonApiAssert\Assert`)

### assertContainsAtLeastOneMember

Asserts that a json object contains at least one member from the expected list.

Definition:

`assertContainsAtLeastOneMember($expected, $json, $message = '')`

Parameters :

- `array<string>` `$expected`
- `array` `$json`
- `string` `$message` (optional)

### assertContainsOnlyAllowedMembers

Asserts that a json object contains only members from the expected list.

Definition :

`assertContainsOnlyAllowedMembers($expected, $json, $message = '')`

Parameters :

- `array<string>` `$expected`
- `array` `$json`
- `string` `$message` (optional)

### assertFieldHasNoForbiddenMemberName

Asserts that a field object (i.e., a resource object’s attributes or one of its relationships) has no forbidden member name.

Definition :

`assertFieldHasNoForbiddenMemberName($field)`

Parameters :

- `array` `$field`

It will do the following checks :

- asserts that each member name of the field is not a forbidden name ([assertIsNotForbiddenMemberName](#assertIsNotForbiddenMemberName)).
- if the field has nested objects, it will checks each all.

### assertHasAttributes

Asserts that a json object has an "attributes" member.

Definition :

`assertHasAttributes($json)`

Parameters :

- `array` `$json`

See [`assertHasMember`](#assertHasMember).

### assertHasData

Asserts that a json object has a "data" member.

Definition :

`assertHasData($json)`

Parameters :

- `array` `$json`

See [`assertHasMember`](#assertHasMember).

### assertHasErrors

Asserts that a json object has an "errors" member.

Definition :

`assertHasErrors($json)`

Parameters :

- `array` `$json`

See [`assertHasMember`](#assertHasMember).

### assertHasIncluded

Asserts that a json object has an "included" member.

Definition :

`assertHasIncluded($json)`

Parameters :

- `array` `$json`

See [`assertHasMember`](#assertHasMember).

### assertHasJsonapi

Asserts that a json object has a "jsonapi" member.

Definition :

`assertHasJsonapi($json)`

Parameters :

- `array` `$json`

See [`assertHasMember`](#assertHasMember).

### assertHasLinks

Asserts that a json object has a "links" member.

Definition :

`assertHasLinks($json)`

Parameters :

- `array` `$json`

See [`assertHasMember`](#assertHasMember).

### assertHasMember

Asserts that a json object has an expected member.

Definition :

`assertHasMember($expected, $json)`

Parameters :

- `string` `$expected`
- `array` `$json`

### assertHasMembers

Asserts that a json object has expected members.

Definition :

`assertHasMembers($expected, $json)`

Parameters :

- `array<string>` `$expected`
- `array` `$json`

### assertHasMeta

Asserts that a json object has a "meta" member.

Definition :

`assertHasMeta($json)`

Parameters :

- `array` `$json`

See [`assertHasMember`](#assertHasMember).

### assertHasOnlyMembers

Asserts that a json object has only expected members.

Definition :

`assertHasOnlyMembers($expected, $json)`

Parameters :

- `array<string>` `$expected`
- `array` `$json`

### assertHasRelationships

Asserts that a json object has a "relationships" member.

Definition :

`assertHasRelationships($json)`

Parameters :

- `array` `$json`

See [`assertHasMember`](#assertHasMember).

### assertHasValidFields

Asserts that a resource object has valid fields (i.e., a resource object’s attributes and its relationships).

Definition :

`assertHasValidFields($resource)`

Parameters :

- `array` `$resource`

It will do the following checks :

- asserts that each attributes member and each relationship name is valid ([assertIsNotForbiddenResourceFieldName](#assertIsNotForbiddenResourceFieldName)).

### assertHasValidStructure

Asserts that a json document has valid structure.

Definition :

`assertHasValidStructure($json, $strict)`

Parameters :

- `array` `$json`
- `boolean` `$strict` if true, unsafe characters are not allowed when checking members name.

It will do the following checks :

- checks top-level members ([assertHasValidTopLevelMembers](#assertHasValidTopLevelMembers))

Optionaly, if presents, it will checks :

- primary data ([assertIsValidPrimaryData](#assertIsValidPrimaryData))
- errors object ([assertIsValidErrorsObject](#assertIsValidErrorsObject))
- meta object ([assertIsValidMetaObject](#assertIsValidMetaObject))
- jsonapi object ([assertIsValidJsonapiObject](#assertIsValidJsonapiObject))
- top-level links object ([assertIsValidTopLevelLinksMember](#assertIsValidTopLevelLinksMember))
- included object ([assertIsValidIncludedCollection](#assertIsValidIncludedCollection))

### assertHasValidTopLevelMembers

Asserts that a json document has valid top-level structure.

Definition :

`assertHasValidTopLevelMembers($json)`

Parameters :

- `array` `$json`

It will do the following checks :

- asserts that the json document contains at least one of the following top-level members : "data", "meta" or "errors" ([assertContainsAtLeastOneMember](#assertContainsAtLeastOneMember)).
- asserts that the members "data" and "errors" does not coexist in the same document.
- asserts that the json document contains only the following members : "data", "errors", "meta", "jsonapi", "links", "included" ([assertContainsOnlyAllowedMembers](#assertContainsOnlyAllowedMembers)).
- if the json document does not contain a top-level "data" member, the "included" member must not be present either.

### assertIsArrayOfObjects

Asserts that an array is an array of objects.

Definition :

`assertIsArrayOfObjects($json, $message = '')`

Parameters :

- `array` `$json`
- `string` `$message` (optional)

### assertIsNotArrayOfObjects

Asserts that an array is not an array of objects.

Definition :

`assertIsNotArrayOfObjects($json, $message = '')`

Parameters :

- `array` `$json`
- `string` `$message` (optional)

### assertIsNotForbiddenMemberName

Asserts that a member name is not a forbidden name like "relationships" or "links".

Definition :

`assertIsNotForbiddenMemberName($name)`

Parameters :

- `string` `$name`

### assertIsNotForbiddenResourceFieldName

Asserts that a resource field name is not a forbidden name (like "type" or "id").

Definition :

`assertIsNotForbiddenResourceFieldName(string $name)`

Parameters :

- `string` `$name`

### assertIsValidAttributesObject

Asserts that a json fragment is a valid attributes object.

Definition :

`assertIsValidAttributesObject($json, $strict)`

Parameters :

- `array` `$json`
- `boolean` `$strict` if true, unsafe characters are not allowed when checking members name.

It will do the following checks :

- asserts that attributes object is not an array of objects ([assertIsNotArrayOfObjects](#assertIsNotArrayOfObjects)).
- asserts that attributes object has no member with forbidden name ([assertFieldHasNoForbiddenMemberName](#assertFieldHasNoForbiddenMemberName)).
- asserts that each member name of the attributes object is valid ([assertIsValidMemberName](#assertIsValidMemberName)).

### assertIsValidErrorLinksObject

Asserts that a json fragment is a valid error links object.

Definition :

`assertIsValidErrorLinksObject($json, $strict)`

Parameters :

- `array` `$json`
- `boolean` `$strict` if true, unsafe characters are not allowed when checking members name.

It will do the following checks :

- asserts that le links object is valid ([assertIsValidLinksObject](#assertIsValidLinksObject) with only "about" member allowed).

### assertIsValidErrorObject

Asserts that a json fragment is a valid error object.

Definition :

`assertIsValidErrorObject($json, $strict)`

Parameters :

- `array` `$json`
- `boolean` `$strict` if true, unsafe characters are not allowed when checking members name.

It will do the following checks :

- asserts that the error object is not empty.
- asserts it contains only the following allowed members : "id", "links", "status", "code", "title", "details", "source", "meta" ([assertContainsOnlyAllowedMembers](#assertContainsOnlyAllowedMembers)).
- if present, asserts that the "status" member is a string.
- if present, asserts that the "code" member is a string.
- if present, asserts that the "title" member is a string.
- if present, asserts that the "details" member is a string.
- if present, asserts that the "source" member is valid([assertIsValidErrorSourceObject](#assertIsValidErrorSourceObject)).
- if present, asserts that the "links" member is valid([assertIsValidErrorLinksObject](#assertIsValidErrorLinksObject)).
- if present, asserts that the "meta" member is valid([assertIsValidMetaObject](#assertIsValidMetaObject)).

### assertIsValidErrorsObject

Asserts that a json fragment is a valid errors object.

Definition :

`assertIsValidErrorsObject($json, $strict)`

Parameters :

- `array` `$json`
- `boolean` `$strict` if true, unsafe characters are not allowed when checking members name.

It will do the following checks :

- asserts that the errors object is an array of objects ([assertIsArrayOfObjects](#assertIsArrayOfObjects)).
- asserts that each error object of the collection is valid ([assertIsValidErrorObject](#assertIsValidErrorObject)).

### assertIsValidErrorSourceObject

Asserts that a json fragment is a valid error source object.

Definition :

`assertIsValidErrorSourceObject($json)`

Parameters :

- `array` `$json`

It will do the following checks :

- if the "pointer" member is present, asserts it is a string starting with a "/" character.
- if the "parameter" member is present, asserts that it is a string.

### assertIsValidIncludedCollection

Asserts that a collection of included resources is valid.

Definition :

`assertIsValidIncludedCollection($included, $data, $strict)`

Parameters :

- `array` `$included` The included top-level member of the json document.
- `array` `$data` The primary data of the json document.
- `boolean` `$strict` if true, unsafe characters are not allowed when checking members name.

It will do the following checks :

- asserts that it is an array of objects ([assertIsArrayOfObjects](#assertIsArrayOfObjects)).
- asserts that each resource of the collection is valid ([assertIsValidResourceObject](#assertIsValidResourceObject)).
- asserts that each resource in the collection corresponds to an existing resource linkage present in either primary data, primary data relationships or another included resource.
- asserts that each resource in the collection is unique (i.e. each couple id-type is unique).

### assertIsValidJsonapiObject

Asserts that a json fragment is a valid jsonapi object.

Definition :

`assertIsValidJsonapiObject($json, $strict)`

Parameters :

- `array` `$json`
- `boolean` `$strict` if true, unsafe characters are not allowed when checking members name.

It will do the following checks :

- asserts that the jsonapi object is not an array of objects ([assertIsNotArrayOfObjects](#assertIsNotArrayOfObjects)).
- asserts that the jsonapi object contains only the following allowed members : "version" and "meta" ([assertContainsOnlyAllowedMembers](#assertContainsOnlyAllowedMembers)).
- if present, asserts that the version member is a string.
- if present, asserts that meta member is valid ([assertIsValidMetaObject](#assertIsValidMetaObject)).

### assertIsValidLinkObject

Asserts that a json fragment is a valid link object.

Definition :

`assertIsValidLinkObject($json, $strict)`

Parameters :

- `array` `$json`
- `boolean` `$strict` if true, unsafe characters are not allowed when checking members name.

It will do the following checks :

- asserts that the link object is a string, an array or the `null` value.
- in case it is an array :
  - asserts that it has the "href" member.
  - asserts that it contains only the following allowed members : "href" and "meta" ([assertContainsOnlyAllowedMembers](#assertContainsOnlyAllowedMembers)).
  - if present, asserts that the "meta" object is valid ([assertIsValidMetaObject](#assertIsValidMetaObject)).

### assertIsValidLinksObject

Asserts that a json fragment is a valid links object.

Definition :

`assertIsValidLinksObject($json, $allowedMembers, $strict)`

Parameters :

- `array` `$json`
- `array<string>` `$allowedMembers`
- `boolean` `$strict` if true, unsafe characters are not allowed when checking members name.

It will do the following checks :

- asserts that it contains only allowed members ([assertContainsOnlyAllowedMembers](#assertContainsOnlyAllowedMembers)).
- asserts that each member of the links object is a valid link object ([assertIsValidLinkObject](#assertIsValidLinkObject)).

### assertIsValidMemberName

Asserts that a member name is valid.

Definition :

`assertIsValidMemberName($name, $strict)`

Parameters :

- `string` `$name`
- `boolean` `$strict` if true, unsafe characters are not allowed when checking members name.

It will do the following checks :

- asserts that the name is a string with at least one character.
- asserts that the name has only allowed characters (see the list [here](https://jsonapi.org/format/#document-member-names-allowed-characters)).
- asserts that it starts and ends with a globally allowed character (see the list [here](https://jsonapi.org/format/#document-member-names-allowed-characters)).

### assertIsValidMetaObject

Asserts that a json fragment is a valid meta object.

Definition :

`assertIsValidMetaObject($json, $strict)`

Parameters :

- `array` `$json`
- `boolean` `$strict` if true, unsafe characters are not allowed when checking members name.

It will do the following checks :

- asserts that the meta object is not an array of objects ([assertIsNotArrayOfObjects](#assertIsNotArrayOfObjects)).
- asserts that each member of the meta object is valid ([assertIsValidMemberName](#assertIsValidMemberName)).

### assertIsValidPrimaryData

Asserts a json fragment is a valid primary data object.

Definition :

`assertIsValidPrimaryData($json, $strict)`

Parameters :

- `array` `$json`
- `boolean` `$strict` if true, unsafe characters are not allowed when checking members name.

It will do the following checks :

- asserts that the primary data is either an object, an array of objects or the `null` value.
- if the primary data is not null, checks if it is a valid single resource or a valid resource collection ([assertIsValidResourceObject](#assertIsValidResourceObject) or [assertIsValidResourceIdentifierObject](#assertIsValidResourceIdentifierObject)).

### assertIsValidRelationshipLinksObject

Asserts that a json fragment is a valid link object extracted from a relationship object.

Definition :

`assertIsValidRelationshipLinksObject($json, $withPagination, $strict)`

Parameters :

- `array` `$json`
- `boolean` `$withPagination`
- `boolean` `$strict` if true, unsafe characters are not allowed when checking members name.

It will do the following checks :

- asserts that the links object is valid ([assertIsValidLinksObject](#assertIsValidLinksObject)) with the following allowed members : "self", "related" and eventually pagination links ("first", "last", "prev" and "next").

### assertIsValidRelationshipObject

Asserts that a json fragment is a valid relationship object.

Definition :

`assertIsValidRelationshipObject($json, $strict)`

Parameters :

- `array` `$json`
- `boolean` `$strict` if true, unsafe characters are not allowed when checking members name.

It will do the following checks :

- asserts that the relationship object contains at least one of the following member : "links", "data", "meta" ([assertContainsAtLeastOneMember](#assertContainsAtLeastOneMember)).
- if present, asserts that the data member is valid ([assertIsValidResourceLinkage](#assertIsValidResourceLinkage)).
- if present, asserts that the links member is valid ([assertIsValidRelationshipLinksObject](#assertIsValidRelationshipLinksObject)).
- if present, asserts that the meta object is valid ([assertIsValidMetaObject](#assertIsValidMetaObject)).

### assertIsValidRelationshipsObject

Asserts that a json fragment is a valid relationships object.

Definition :

`assertIsValidRelationshipsObject($json, $strict)`

Parameters :

- `array` `$json`
- `boolean` `$strict` if true, unsafe characters are not allowed when checking members name.

It will do the following checks :

- asserts that the relationships object is not an array of objects ([assertIsNotArrayOfObjects](#assertIsNotArrayOfObjects)).
- asserts that each relationship of the collection has a valid name ([assertIsValidMemberName](#assertIsValidMemberName)) and is a valid relationship object ([assertIsValidRelationshipObject](#assertIsValidRelationshipObject)).

### assertIsValidResourceIdentifierObject

Asserts that a json fragment is a valid resource identifier object.

Definition :

`assertIsValidResourceIdentifierObject($resource, $strict)`

Parameters :

- `array` `$resource`
- `boolean` `$strict` if true, unsafe characters are not allowed when checking members name.

It will do the following checks :

- asserts that the resource as "id" ([assertResourceIdMember](#assertResourceIdMember)) and "type" ([assertResourceTypeMember](#assertResourceTypeMember)) members.
- asserts that it contains only the following allowed members : "id", "type" and "meta" ([assertContainsOnlyAllowedMembers](#assertContainsOnlyAllowedMembers)).
- if present, asserts that the meta object is valid ([assertIsValidMetaObject](#assertIsValidMetaObject)).

### assertIsValidResourceLinkage

Asserts that a json fragment is a valid resource linkage object.

Definition :

`assertIsValidResourceLinkage($json, $strict)`

Parameters :

- `array` `$json`
- `boolean` `$strict` if true, unsafe characters are not allowed when checking members name.

It will do the following checks :

- asserts that the provided resource linkage is either an object, an array of objects or the `null` value.
- asserts that the resource linkage or the collection of resource linkage is valid ([assertIsValidResourceIdentifierObject](#assertIsValidResourceIdentifierObject)).

### assertIsValidResourceLinksObject

Asserts that a json fragment is a valid resource links object.

Definition :

`assertIsValidResourceLinksObject($json, $strict)`

Parameters :

- `array` `$json`
- `boolean` `$strict` if true, unsafe characters are not allowed when checking members name.

It will do the following checks :

- asserts that le links object is valid ([assertIsValidLinksObject](#assertIsValidLinksObject) with only "self" member allowed).

### assertIsValidResourceObject

Asserts that a json fragment is a valid resource.

Definition :

`assertIsValidResourceObject($json, $strict)`

Parameters :

- `array` `$json`
- `boolean` `$strict` if true, unsafe characters are not allowed when checking members name.

It will do the following checks :

- asserts that the resource has valid top-level structure ([assertResourceObjectHasValidTopLevelStructure](#assertResourceObjectHasValidTopLevelStructure)).
- asserts that the "id" member is valid ([assertResourceIdMember](#assertResourceIdMember)).
- asserts that the "type" member is valid ([assertResourceTypeMember](#assertResourceTypeMember)).
- if presents, asserts that the "attributes" member is valid ([assertIsValidAttributesObject](#assertIsValidAttributesObject)).
- if presents, asserts that the "relationships" member is valid ([assertIsValidRelationshipsObject](#assertIsValidRelationshipsObject)).
- if presents, asserts that the "links" member is valid ([assertIsValidResourceLinksObject](#assertIsValidResourceLinksObject)).
- if presents, asserts that the "meta" member is valid ([assertIsValidMetaObject](#assertIsValidMetaObject)).
- asserts that the resource has valid fields ([assertHasValidFields](#assertHasValidFields)).

### assertIsValidResourceObjectCollection

Asserts that a json fragment is a valid collection of resource objects.

Definition :

`assertIsValidResourceObjectCollection($json, $strict)`

Parameters :

- `array` `$json`
- `boolean` `$strict` if true, unsafe characters are not allowed when checking members name.

It will do the following checks :

- asserts that the provided resource collection is either an empty array or an array of objects.
- asserts that the collection of resources is valid ([assertIsValidResourceObject](#assertIsValidResourceObject)).

### assertIsValidTopLevelLinksMember

Asserts that a json fragment is a valid top-level links member.

Definition :

`assertIsValidTopLevelLinksMember($json, $strict)`

Parameters :

- `array` `$json`
- `boolean` `$strict` if true, unsafe characters are not allowed when checking members name.

It will do the following checks :

- asserts that the top-level "links" member contains only the following allowed members : "self", "related", "first", "last", "next", "prev" ([assertIsValidLinksObject](#assertIsValidLinksObject)).

### assertNotHasMember

Asserts that a json object not has an unexpected member.

Definition :

`assertNotHasMember($expected, $json)`

Parameters :

- `string` `$expected`
- `array` `$json`

### assertNotHasMembers

Asserts that a json object not has unexpected members.

Definition :

`assertNotHasMembers($expected, $json)`

Parameters :

- `array<string>` `$expected`
- `array` `$json`

### assertResourceIdMember

Asserts that a resource id member is valid.

Definition :

`assertResourceIdMember($resource)`

Parameters :

- `array` `$resource`

It will do the following checks :

- asserts that the "id" member is not empty.
- asserts that the "id" member is a string.

### assertResourceObjectHasValidTopLevelStructure

Asserts that a resource object has a valid top-level structure.

Definition :

`assertResourceObjectHasValidTopLevelStructure($resource)`

Parameters :

- `array` `$resource`

It will do the following checks :

- asserts that the resource has an "id" member.
- asserts that the resource has a "type" member.
- asserts that the resource contains at least one of the following members : "attributes", "relationships", "links", "meta" ([assertContainsAtLeastOneMember](#assertContainsAtLeastOneMember)).
- asserts that the resource contains only the following allowed members : "id", "type", "meta", "attributes", "links", "relationships" ([assertContainsOnlyAllowedMembers](#assertContainsOnlyAllowedMembers)).

### assertResourceTypeMember

Asserts that a resource type member is valid.

Definition :

`assertResourceTypeMember($resource, $strict)`

Parameters :

- `array` `$resource`
- `boolean` `$strict` if true, unsafe characters are not allowed when checking members name.

It will do the following checks :

- asserts that the "type" member is not empty.
- asserts that the "type" member is a string.
- asserts that the "type" member has a valid value ([assertIsValidMemberName](#assertIsValidMemberName)).

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

```sh
composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

[Vincent Girol](mailto:vincent@girol.fr)

## License

This project is licensed under the [MIT](https://choosealicense.com/licenses/mit/) License.
