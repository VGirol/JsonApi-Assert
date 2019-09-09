<?php
declare(strict_types = 1);

namespace VGirol\JsonApiAssert\Asserts\Structure;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Members;
use VGirol\JsonApiAssert\Messages;

/**
 * Assertions relating to the jsonapi object
 */
trait AssertJsonapiObject
{
    /**
     * Asserts that a json fragment is a valid jsonapi object.
     *
     * @param array     $json
     * @param boolean   $strict         If true, unsafe characters are not allowed when checking members name.
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertIsValidJsonapiObject($json, bool $strict): void
    {
        static::assertIsNotArrayOfObjects(
            $json,
            Messages::OBJECT_NOT_ARRAY
        );

        $allowed = [
            Members::VERSION,
            Members::META
        ];
        static::assertContainsOnlyAllowedMembers(
            $allowed,
            $json
        );

        if (isset($json[Members::VERSION])) {
            PHPUnit::assertIsString(
                $json[Members::VERSION],
                Messages::JSONAPI_VERSION_IS_NOT_STRING
            );
        }

        if (isset($json[Members::META])) {
            static::assertIsValidMetaObject($json[Members::META], $strict);
        }
    }
}
