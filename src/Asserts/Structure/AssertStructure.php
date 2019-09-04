<?php

declare(strict_types=1);

namespace VGirol\JsonApiAssert\Asserts\Structure;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Members;
use VGirol\JsonApiAssert\Messages;

/**
 * Assertions relating to the overall structure of the document
 */
trait AssertStructure
{
    /**
     * Asserts that a json document has valid structure.
     *
     * @param array     $json
     * @param boolean   $strict     If true, unsafe characters are not allowed when checking members name.
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertHasValidStructure($json, bool $strict): void
    {
        static::assertHasValidTopLevelMembers($json);

        if (isset($json[Members::DATA])) {
            static::assertIsValidPrimaryData($json[Members::DATA], $strict);

            if (isset($json[Members::INCLUDED])) {
                static::assertIsValidIncludedCollection($json[Members::INCLUDED], $json[Members::DATA], $strict);
            }
        }

        if (isset($json[Members::ERRORS])) {
            static::assertIsValidErrorsObject($json[Members::ERRORS], $strict);
        }

        if (isset($json[Members::META])) {
            static::assertIsValidMetaObject($json[Members::META], $strict);
        }

        if (isset($json[Members::JSONAPI])) {
            static::assertIsValidJsonapiObject($json[Members::JSONAPI], $strict);
        }

        if (isset($json[Members::LINKS])) {
            static::assertIsValidTopLevelLinksMember($json[Members::LINKS], $strict);
        }
    }

    /**
     * Asserts that a json document has valid top-level structure.
     *
     * @param array $json
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertHasValidTopLevelMembers($json): void
    {
        $expected = [
            Members::DATA,
            Members::ERRORS,
            Members::META
        ];
        static::assertContainsAtLeastOneMember(
            $expected,
            $json,
            \sprintf(Messages::TOP_LEVEL_MEMBERS, implode('", "', $expected))
        );

        PHPUnit::assertFalse(
            isset($json[Members::DATA]) && isset($json[Members::ERRORS]),
            Messages::TOP_LEVEL_DATA_AND_ERROR
        );

        $allowed = [
            Members::DATA,
            Members::ERRORS,
            Members::META,
            Members::JSONAPI,
            Members::LINKS,
            Members::INCLUDED
        ];
        static::assertContainsOnlyAllowedMembers(
            $allowed,
            $json
        );

        PHPUnit::assertFALSE(
            !isset($json[Members::DATA]) && isset($json[Members::INCLUDED]),
            Messages::TOP_LEVEL_DATA_AND_INCLUDED
        );
    }

    /**
     * Asserts that a json fragment is a valid top-level links member.
     *
     * @param array     $json
     * @param boolean   $strict     If true, unsafe characters are not allowed when checking members name.
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertIsValidTopLevelLinksMember($json, bool $strict): void
    {
        $allowed = [
            Members::SELF,
            Members::RELATED,
            Members::FIRST,
            Members::LAST,
            Members::NEXT,
            Members::PREV
        ];
        static::assertIsValidLinksObject($json, $allowed, $strict);
    }

    /**
     * Asserts a json fragment is a valid primary data object.
     *
     * @param array|null    $json
     * @param boolean       $strict     If true, unsafe characters are not allowed when checking members name.
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertIsValidPrimaryData($json, bool $strict): void
    {
        if ($json === null) {
            $json = [];
        }

        PHPUnit::assertIsArray(
            $json,
            Messages::PRIMARY_DATA_NOT_ARRAY
        );

        if (empty($json)) {
            return;
        }

        if (static::isArrayOfObjects($json)) {
            // Resource collection (Resource Objects or Resource Identifier Objects)
            static::assertIsValidPrimaryCollection($json, true, $strict);

            return;
        }

        // Single Resource (Resource Object or Resource Identifier Object)
        static::assertIsValidPrimarySingle($json, $strict);
    }

    /**
     * Asserts that a collection of resource object is valid.
     *
     * @param array     $list
     * @param boolean   $checkType      If true, asserts that all resources of the collection are of same type.
     * @param boolean   $strict         If true, excludes not safe characters when checking members name
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    private static function assertIsValidPrimaryCollection($list, bool $checkType, bool $strict): void
    {
        $isResourceObject = null;
        foreach ($list as $index => $resource) {
            if ($checkType) {
                // Assert that all resources of the collection are of same type.
                if ($index == 0) {
                    $isResourceObject = static::dataIsResourceObject($resource);
                    continue;
                }

                PHPUnit::assertEquals(
                    $isResourceObject,
                    static::dataIsResourceObject($resource),
                    Messages::PRIMARY_DATA_SAME_TYPE
                );
            }

            // Check the resource
            static::assertIsValidPrimarySingle($resource, $strict);
        }
    }

    /**
     * Assert that a single resource object is valid.
     *
     * @param array     $resource
     * @param boolean   $strict     If true, excludes not safe characters when checking members name
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    private static function assertIsValidPrimarySingle($resource, bool $strict): void
    {
        if (static::dataIsResourceObject($resource)) {
            static::assertIsValidResourceObject($resource, $strict);

            return;
        }

        static::assertIsValidResourceIdentifierObject($resource, $strict);
    }

    /**
     * Asserts that a collection of included resources is valid.
     *
     * @param array     $included   The included top-level member of the json document.
     * @param array     $data       The primary data of the json document.
     * @param boolean   $strict     If true, unsafe characters are not allowed when checking members name.
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertIsValidIncludedCollection($included, $data, bool $strict): void
    {
        static::assertIsValidResourceObjectCollection($included, $strict);

        $resIdentifiers = array_merge(
            static::getAllResourceIdentifierObjects($data),
            static::getAllResourceIdentifierObjects($included)
        );

        $present = [];
        foreach ($included as $inc) {
            PHPUnit::assertTrue(
                self::existsInArray($inc, $resIdentifiers),
                Messages::INCLUDED_RESOURCE_NOT_LINKED
            );

            if (!isset($present[$inc[Members::TYPE]])) {
                $present[$inc[Members::TYPE]] = [];
            }
            PHPUnit::assertNotContains(
                $inc[Members::ID],
                $present[$inc[Members::TYPE]],
                Messages::COMPOUND_DOCUMENT_ONLY_ONE_RESOURCE
            );
            array_push($present[$inc[Members::TYPE]], $inc[Members::ID]);
        }
    }

    /**
     * Checks if a given json fragment is a resource object.
     *
     * @param array $resource
     * @return bool
     */
    private static function dataIsResourceObject($resource): bool
    {
        $expected = [
            Members::ATTRIBUTES,
            Members::RELATIONSHIPS,
            Members::LINKS
        ];

        return static::containsAtLeastOneMember($expected, $resource);
    }

    /**
     * Get all the resource identifier objects (resource linkage) presents in a collection of resource.
     *
     * @param array $data
     * @return array
     */
    private static function getAllResourceIdentifierObjects($data): array
    {
        $arr = [];
        if (empty($data)) {
            return $arr;
        }
        if (!static::isArrayOfObjects($data)) {
            $data = [$data];
        }
        foreach ($data as $obj) {
            if (!isset($obj[Members::RELATIONSHIPS])) {
                continue;
            }
            foreach ($obj[Members::RELATIONSHIPS] as $relationship) {
                if (!isset($relationship[Members::DATA])) {
                    continue;
                }
                $arr = array_merge(
                    $arr,
                    static::isArrayOfObjects($relationship[Members::DATA]) ?
                        $relationship[Members::DATA] : [$relationship[Members::DATA]]
                );
            }
        }

        return $arr;
    }

    /**
     * Checks if a resource is present in a given array.
     *
     * @param array $needle
     * @param array $arr
     * @return bool
     */
    private static function existsInArray($needle, $arr): bool
    {
        foreach ($arr as $resIdentifier) {
            $test = $resIdentifier[Members::TYPE] === $needle[Members::TYPE]
                && $resIdentifier[Members::ID] === $needle[Members::ID];
            if ($test) {
                return true;
            }
        }

        return false;
    }
}
