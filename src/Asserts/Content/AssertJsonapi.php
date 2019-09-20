<?php

declare(strict_types=1);

namespace VGirol\JsonApiAssert\Asserts\Content;

use PHPUnit\Framework\Assert as PHPUnit;

/**
 * This trait adds the ability to test jsonapi object content.
 */
trait AssertJsonapi
{
    /**
     * Asserts that a jsonapi object equals an expected array.
     *
     * @link https://jsonapi.org/format/#document-jsonapi-object
     *
     * @param array $expected The expected jsonapi object
     * @param array $json     The jsonapi object to test
     *
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertJsonapiObjectEquals($expected, $json): void
    {
        PHPUnit::assertSame($expected, $json);
    }
}
