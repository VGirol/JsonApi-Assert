<?php

declare(strict_types=1);

namespace VGirol\JsonApiAssert\Asserts\Content;

/**
 * This trait adds the ability to test include content.
 */
trait AssertInclude
{
    /**
     * Asserts that an array of included resource objects contains a given resource object or collection.
     *
     * (@see assertResourceCollectionContains)
     *
     * @param array $expected A single resource object or an array of expected resource objects
     * @param array $json     The array of included resource objects to inspect
     *
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertIncludeObjectContains($expected, $json)
    {
        if (!\is_array($expected)) {
            static::invalidArgument(
                1,
                'array',
                $expected
            );
        }

        static::assertResourceCollectionContains($expected, $json);
    }

    /**
     * Asserts that a JSON document contains an expected included collection.
     *
     * @param array $expected A single resource object or an array of expected resource objects
     * @param array $json     The JSON document to be tested
     *
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertDocumentContainsInclude($expected, $json)
    {
        static::assertHasMember(Members::INCLUDED, $json);

        $included = $json[Members::INCLUDED];

        static::assertIncludeObjectContains($expected, $included);
    }
}
