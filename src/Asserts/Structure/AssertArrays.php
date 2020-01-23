<?php

declare(strict_types=1);

namespace VGirol\JsonApiAssert\Asserts\Structure;

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
        static::askService('isArrayOfObjects', $json, false, $message);
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
        static::askService('isNotArrayOfObjects', $json, false, $message);
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
        return static::proxyService('isArrayOfObjects', $arr, true);
    }
}
