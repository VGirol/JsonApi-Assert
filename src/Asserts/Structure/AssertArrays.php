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
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public static function assertIsArrayOfObjects($json, $message = ''): void
    {
        static::askService('mustBeArrayOfObjects', $json, $message);
    }

    /**
     * Asserts that an array is not an array of objects.
     *
     * @param array  $json
     * @param string $message An optional message to explain why the test failed
     *
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public static function assertIsNotArrayOfObjects($json, $message = ''): void
    {
        static::askService('mustNotBeArrayOfObjects', $json, $message);
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
        return static::proxyService('isArrayOfObjects', $arr);
    }
}
