<?php

declare(strict_types=1);

namespace VGirol\JsonApiAssert\Asserts\Structure;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Members;
use VGirol\JsonApiAssert\Messages;

/**
 * Assertions relating to the resource object
 */
trait AssertResourceObject
{
    /**
     * Asserts that a json fragment is a valid collection of resource objects.
     *
     * It will do the following checks :
     * 1) asserts that the provided resource collection is either an empty array or an array of objects
     * (@see assertIsArrayOfObjects).
     * 2) asserts that the collection of resources is valid (@see assertIsValidResourceObject).
     *
     * @param array|null $json
     * @param boolean    $strict If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertIsValidResourceObjectCollection($json, bool $strict): void
    {
        PHPUnit::assertIsArray(
            $json,
            Messages::RESOURCE_COLLECTION_NOT_ARRAY
        );

        if (empty($json)) {
            return;
        }

        static::assertIsArrayOfObjects($json);

        foreach ($json as $resource) {
            static::assertIsValidResourceObject($resource, $strict);
        }
    }

    /**
     * Asserts that a json fragment is a valid resource.
     *
     * It will do the following checks :
     * 1) asserts that the resource object has valid top-level structure
     * (@see assertResourceObjectHasValidTopLevelStructure).
     * 2) asserts that the resource object has valid "type" and "id" members
     * (@see assertResourceIdMember and @see assertResourceTypeMember).
     * 3) asserts that the resource object has valid fields (@see assertHasValidFields).
     *
     * Optionaly, if presents, it will checks :
     * 4) asserts thats the resource object has valid "attributes" member.
     * 5) asserts thats the resource object has valid "relationships" member.
     * 6) asserts thats the resource object has valid "links" member.
     * 7) asserts thats the resource object has valid "meta" member.
     *
     * @param array   $json
     * @param boolean $strict If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertIsValidResourceObject($json, bool $strict): void
    {
        static::assertResourceObjectHasValidTopLevelStructure($json);
        static::assertResourceIdMember($json);
        static::assertResourceTypeMember($json, $strict);

        if (isset($json[Members::ATTRIBUTES])) {
            static::assertIsValidAttributesObject($json[Members::ATTRIBUTES], $strict);
        }

        if (isset($json[Members::RELATIONSHIPS])) {
            static::assertIsValidRelationshipsObject($json[Members::RELATIONSHIPS], $strict);
        }

        if (isset($json[Members::LINKS])) {
            static::assertIsValidResourceLinksObject($json[Members::LINKS], $strict);
        }

        if (isset($json[Members::META])) {
            static::assertIsValidMetaObject($json[Members::META], $strict);
        }

        static::assertHasValidFields($json);
    }

    /**
     * Asserts that a resource object has a valid top-level structure.
     *
     * It will do the following checks :
     * 1) asserts that the resource has an "id" member.
     * 2) asserts that the resource has a "type" member.
     * 3) asserts that the resource contains at least one of the following members :
     * "attributes", "relationships", "links", "meta" (@see assertContainsAtLeastOneMember).
     * 4) asserts that the resource contains only the following allowed members :
     * "id", "type", "meta", "attributes", "links", "relationships" (@see assertContainsOnlyAllowedMembers).
     *
     * @param array $resource
     *
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertResourceObjectHasValidTopLevelStructure($resource): void
    {
        PHPUnit::assertIsArray(
            $resource,
            Messages::RESOURCE_IS_NOT_ARRAY
        );

        PHPUnit::assertArrayHasKey(
            Members::ID,
            $resource,
            Messages::RESOURCE_ID_MEMBER_IS_ABSENT
        );

        PHPUnit::assertArrayHasKey(
            Members::TYPE,
            $resource,
            Messages::RESOURCE_TYPE_MEMBER_IS_ABSENT
        );

        static::assertContainsAtLeastOneMember(
            [
                Members::ATTRIBUTES,
                Members::RELATIONSHIPS,
                Members::LINKS,
                Members::META
            ],
            $resource
        );

        static::assertContainsOnlyAllowedMembers(
            [
                Members::ID,
                Members::TYPE,
                Members::META,
                Members::ATTRIBUTES,
                Members::LINKS,
                Members::RELATIONSHIPS
            ],
            $resource
        );
    }

    /**
     * Asserts that a resource id member is valid.
     *
     * It will do the following checks :
     * 1) asserts that the "id" member is not empty.
     * 2) asserts that the "id" member is a string.
     *
     * @param array $resource
     *
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertResourceIdMember($resource): void
    {
        PHPUnit::assertIsString(
            $resource[Members::ID],
            Messages::RESOURCE_ID_MEMBER_IS_NOT_STRING
        );

        PHPUnit::assertNotEmpty(
            // PHP treats 0 and '0' as empty.
            str_replace('0', 'zero', $resource[Members::ID]),
            Messages::RESOURCE_ID_MEMBER_IS_EMPTY
        );
    }

    /**
     * Asserts that a resource type member is valid.
     *
     * It will do the following checks :
     * 1) asserts that the "type" member is not empty.
     * 2) asserts that the "type" member is a string.
     * 3) asserts that the "type" member has a valid value (@see assertIsValidMemberName).
     *
     * @param array   $resource
     * @param boolean $strict   If true, excludes not safe characters when checking members name
     *
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertResourceTypeMember($resource, bool $strict): void
    {
        PHPUnit::assertNotEmpty(
            $resource[Members::TYPE],
            Messages::RESOURCE_TYPE_MEMBER_IS_EMPTY
        );

        PHPUnit::assertIsString(
            $resource[Members::TYPE],
            Messages::RESOURCE_TYPE_MEMBER_IS_NOT_STRING
        );

        static::assertIsValidMemberName(
            $resource[Members::TYPE],
            $strict
        );
    }

    /**
     * Asserts that a json fragment is a valid resource links object.
     *
     * It will do the following checks :
     * 1) asserts that le links object is valid (@see assertIsValidLinksObject) with only "self" member allowed.
     *
     * @param array   $json
     * @param boolean $strict If true, excludes not safe characters when checking members name
     *
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertIsValidResourceLinksObject($json, bool $strict): void
    {
        static::assertIsValidLinksObject(
            $json,
            [
                Members::LINK_SELF
            ],
            $strict
        );
    }

    /**
     * Asserts that a resource object has valid fields (i.e., resource object’s attributes and its relationships).
     *
     * It will do the following checks :
     * 1) asserts that each attributes member and each relationship name is valid
     * (@see assertIsNotForbiddenResourceFieldName)
     *
     * @param array $resource
     *
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertHasValidFields($resource): void
    {
        if (isset($resource[Members::ATTRIBUTES])) {
            foreach (array_keys($resource[Members::ATTRIBUTES]) as $name) {
                static::assertIsNotForbiddenResourceFieldName($name);
            }
        }

        if (isset($resource[Members::RELATIONSHIPS])) {
            foreach (array_keys($resource[Members::RELATIONSHIPS]) as $name) {
                static::assertIsNotForbiddenResourceFieldName($name);

                if (isset($resource[Members::ATTRIBUTES])) {
                    PHPUnit::assertArrayNotHasKey(
                        $name,
                        $resource[Members::ATTRIBUTES],
                        Messages::FIELDS_HAVE_SAME_NAME
                    );
                }
            }
        }
    }

    /**
     * Asserts that a resource field name is not a forbidden name (like "type" or "id").
     *
     * @param string $name
     *
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertIsNotForbiddenResourceFieldName(string $name): void
    {
        PHPUnit::assertNotContains(
            $name,
            [
                Members::TYPE,
                Members::ID
            ],
            Messages::FIELDS_NAME_NOT_ALLOWED
        );
    }
}
