<?php

declare(strict_types=1);

namespace VGirol\JsonApiAssert\Asserts\Content;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Messages;

/**
 * This trait adds the ability to test resource object (single or collection).
 */
trait AssertResource
{
    /**
     * Asserts that a resource object equals a given resource object.
     *
     * @link https://jsonapi.org/format/#document-resource-objects
     *
     * @param array $expected
     * @param array $json
     *
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public static function assertResourceObjectEquals($expected, $json)
    {
        PHPUnit::assertEquals(
            $expected,
            $json,
            sprintf(Messages::RESOURCE_IS_NOT_EQUAL, var_export($json, true), var_export($expected, true))
        );
    }

    /**
     * Asserts that an array of resource objects is equal to a given collection (same values and same order).
     *
     * It will do the following checks :
     * 1) asserts that the array to check is an array of objects (@see assertIsArrayOfObjects).
     * 2) asserts that the two arrays have the same count of items.
     * 3) asserts that each resource object of the array correspond to the resource object
     * at the same index in the given expected array (@see assertResourceObjectEquals).
     *
     * @link https://jsonapi.org/format/#document-resource-objects
     *
     * @param array $expected
     * @param array $json
     *
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public static function assertResourceCollectionEquals($expected, $json)
    {
        static::assertIsArrayOfObjects($expected);

        $count = count($json);
        $expectedCount = count($expected);
        PHPUnit::assertEquals(
            $expectedCount,
            $count,
            sprintf(Messages::RESOURCE_COLLECTION_HAVE_NOT_SAME_LENGTH, $count, $expectedCount)
        );

        foreach ($expected as $index => $resource) {
            static::assertResourceObjectEquals($resource, $json[$index]);
        }
    }

    /**
     * Asserts that an array of resource objects contains a given subset of resource objects.
     *
     * @link https://jsonapi.org/format/#document-resource-objects
     *
     * @param array $expected
     * @param array $json
     *
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public static function assertResourceCollectionContains($expected, $json)
    {
        if (!static::isArrayOfObjects($expected)) {
            $expected = [$expected];
        }

        foreach ($expected as $resource) {
            PHPUnit::assertContains($resource, $json);
        }
    }
}
