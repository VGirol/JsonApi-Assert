<?php

declare(strict_types=1);

namespace VGirol\JsonApiAssert\Asserts\Structure;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Messages;

/**
 * Assertions relating to the arrays
 */
trait AssertArrays
{
    /**
     * Asserts that an array is an array of objects.
     *
     * @param array  $json
     * @param string $message An optional message to explain why the test failed
     *
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertIsArrayOfObjects($json, $message = ''): void
    {
        if (!\is_array($json)) {
            static::invalidArgument(1, 'array', $json);
        }

        $message = $message ?: Messages::MUST_BE_ARRAY_OF_OBJECTS;
        PHPUnit::assertTrue(static::isArrayOfObjects($json), $message);
    }

    /**
     * Asserts that an array is not an array of objects.
     *
     * @param array  $json
     * @param string $message An optional message to explain why the test failed
     *
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertIsNotArrayOfObjects($json, $message = ''): void
    {
        if (!\is_array($json)) {
            static::invalidArgument(1, 'array', $json);
        }

        $message = $message ?: Messages::MUST_NOT_BE_ARRAY_OF_OBJECTS;
        PHPUnit::assertFalse(static::isArrayOfObjects($json), $message);
    }

    /**
     * Checks if the given array is an array of objects.
     *
     * @param array $arr
     *
     * @return boolean
     */
    protected static function isArrayOfObjects(array $arr): bool
    {
        if (empty($arr)) {
            return true;
        }

        return !static::arrayIsAssociative($arr);
    }

    /**
     * Checks if the given array is an associative array.
     *
     * @param array $arr
     *
     * @return boolean
     */
    private static function arrayIsAssociative(array $arr): bool
    {
        return (array_keys($arr) !== range(0, count($arr) - 1));
    }
}
