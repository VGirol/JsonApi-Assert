<?php
declare(strict_types = 1);

namespace VGirol\JsonApiAssert\Asserts\Content;

use PHPUnit\Framework\Assert as PHPUnit;

/**
 * Assertions relating to the pagination
 */
trait AssertJsonapi
{
    /**
     * Asserts that a jsonapi object equals an expected array.
     *
     * @param array $expected
     * @param array $json
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertJsonapiObjectEquals($expected, $json): void
    {
        PHPUnit::assertSame($expected, $json);
    }
}
