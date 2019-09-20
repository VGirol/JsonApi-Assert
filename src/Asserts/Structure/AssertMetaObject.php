<?php

declare(strict_types=1);

namespace VGirol\JsonApiAssert\Asserts\Structure;

use VGirol\JsonApiAssert\Messages;

/**
 * Assertions relating to the meta object
 */
trait AssertMetaObject
{
    /**
     * Asserts that a json fragment is a valid meta object.
     *
     * It will do the following checks :
     * 1) asserts that the meta object is not an array of objects (@see assertIsNotArrayOfObjects).
     * 2) asserts that each member of the meta object is valid (@see assertIsValidMemberName).
     *
     * @param array   $json
     * @param boolean $strict If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertIsValidMetaObject($json, bool $strict): void
    {
        static::assertIsNotArrayOfObjects(
            $json,
            Messages::META_OBJECT_IS_NOT_ARRAY
        );

        foreach (array_keys($json) as $key) {
            static::assertIsValidMemberName($key, $strict);
        }
    }
}
