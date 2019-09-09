<?php
declare(strict_types = 1);

namespace VGirol\JsonApiAssert\Asserts\Content;

trait AssertInclude
{
    /**
     * Asserts that an array of included resource objects contains a given resource object or collection.
     *
     * @param array $expected
     * @param array $json
     */
    public static function assertIncludeObjectContains($expected, $json)
    {
        static::assertResourceCollectionContains($expected, $json);
    }
}
