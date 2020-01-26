<?php

declare(strict_types=1);

namespace VGirol\JsonApiAssert\Asserts\Content;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Messages;

/**
 * This trait adds the ability to test resource linkage (single or collection).
 */
trait AssertResourceLinkage
{
    /**
     * Asserts that a resource identifier object is equal to an expected resource identifier.
     *
     * @link https://jsonapi.org/format/#document-resource-object-linkage
     * @link https://jsonapi.org/format/#document-resource-identifier-objects
     *
     * @param array $expected
     * @param array $json
     *
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public static function assertResourceIdentifierEquals($expected, $json)
    {
        PHPUnit::assertSame(
            $expected,
            $json,
            sprintf(Messages::RESOURCE_IDENTIFIER_IS_NOT_EQUAL, var_export($json, true), var_export($expected, true))
        );
    }

    /**
     * Asserts that an array of resource identifer objects correspond to an expected collection.
     *
     * It will do the following checks :
     * 1) asserts that the given array is an array of objects (@see assertIsArrayOfObjects).
     * 2) asserts that the two arrays have the same count of items.
     * 3) asserts that each resource identifier object of the array equals the resource identifier
     * at the same index in the given expected collection (@see assertResourceIdentifierEquals).
     *
     * @link https://jsonapi.org/format/#document-resource-object-linkage
     * @link https://jsonapi.org/format/#document-resource-identifier-objects
     *
     * @param array $expected
     * @param array $json
     *
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public static function assertResourceIdentifierCollectionEquals($expected, $json)
    {
        static::assertIsArrayOfObjects($json);

        $expectedCount = count($expected);
        $count = count($json);
        PHPUnit::assertEquals(
            $expectedCount,
            $count,
            sprintf(Messages::RESOURCE_LINKAGE_COLLECTION_HAVE_NOT_SAME_LENGTH, $count, $expectedCount)
        );

        $index = 0;
        foreach ($expected as $resource) {
            static::assertResourceIdentifierEquals($resource, $json[$index]);
            $index++;
        }
    }

    /**
     * Asserts that a resource linkage object correspond to a given reference object
     *
     * Asserts that a resource linkage object correspond to a given reference object
     * which can be either the null value, a single resource identifier object,
     * an empty collection or a collection of resource identifier ojects.
     *
     * It will do the following checks :
     * 1) if the expected value is `null`, asserts that the resource linkage is `null`.
     * 2) if the expected value is a single resource identifier object, asserts that the resource linkage
     * is not an array of objects (@see assertIsNotArrayOfObjects)
     * and is equal to the given expected object (@see assertResourceIdentifierEquals).
     * 3) if the expected value is an empty array, asserts that the resource linkage is an empty array.
     * 4) if the expected value is a collection, asserts that the resource linkage corresponds to the given collection
     * (@see assertResourceIdentifierCollectionEquals)
     *
     * @link https://jsonapi.org/format/#document-resource-object-linkage
     * @link https://jsonapi.org/format/#document-resource-identifier-objects
     *
     * @param array|null $expected
     * @param array|null $json
     * @param boolean    $strict   If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public static function assertResourceLinkageEquals($expected, $json, $strict)
    {
        static::assertIsValidResourceLinkage($json, $strict);

        if ($expected === null) {
            PHPUnit::assertNull(
                $json,
                sprintf(Messages::RESOURCE_LINKAGE_MUST_BE_NULL, var_export($json, true))
            );

            return;
        }

        PHPUnit::assertNotNull($json, Messages::RESOURCE_LINKAGE_MUST_NOT_BE_NULL);

        if (!static::isArrayOfObjects($expected)) {
            static::assertIsNotArrayOfObjects($json);
            static::assertResourceIdentifierEquals($expected, $json);

            return;
        }

        if (count($expected) == 0) {
            PHPUnit::assertEmpty($json, Messages::RESOURCE_LINKAGE_COLLECTION_MUST_BE_EMPTY);

            return;
        }

        static::assertResourceIdentifierCollectionEquals($expected, $json);
    }
}
