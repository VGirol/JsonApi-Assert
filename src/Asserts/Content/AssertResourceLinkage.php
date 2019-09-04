<?php

declare(strict_types=1);

namespace VGirol\JsonApiAssert\Asserts\Content;

use PHPUnit\Framework\Assert as PHPUnit;

trait AssertResourceLinkage
{
    /**
     * Asserts that a resource identifier object is equal to an expected resource identifier.
     *
     * @param array $expected
     * @param array $json
     */
    public static function assertResourceIdentifierEquals($expected, $json)
    {
        PHPUnit::assertSame($expected, $json);
    }

    /**
     * Asserts that an array of resource identifer objects correspond to an expected collection.
     *
     * @param array $expected
     * @param array $json
     */
    public static function assertResourceIdentifierCollectionEquals($expected, $json)
    {
        static::assertIsArrayOfObjects($json);
        PHPUnit::assertEquals(count($expected), count($json));

        $index = 0;
        foreach ($expected as $resource) {
            static::assertResourceIdentifierEquals($resource, $json[$index]);
            $index++;
        }
    }

    /**
     * Asserts that a resource linkage object correspond to a given reference object
     * which can be either the null value, a single resource identifier object,
     * an empty collection or a collection of resource identifier ojects.
     *
     * @param array|null $expected
     * @param array|null $json
     * @param boolean   $strict         If true, unsafe characters are not allowed when checking members name.
     */
    public static function assertResourceLinkageEquals($expected, $json, $strict)
    {
        static::assertIsValidResourceLinkage($json, $strict);

        if ($expected === null) {
            PHPUnit::assertNull($json);

            return;
        }

        PHPUnit::assertNotNull($json);
        /** @var array $json */

        if (!static::isArrayOfObjects($expected)) {
            static::assertIsNotArrayOfObjects($json);
            static::assertResourceIdentifierEquals($expected, $json);

            return;
        }

        if (count($expected) == 0) {
            PHPUnit::assertEmpty($json);

            return;
        }

        static::assertResourceIdentifierCollectionEquals($expected, $json);
    }
}
