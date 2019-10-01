<?php

declare(strict_types=1);

namespace VGirol\JsonApiAssert\Asserts\Structure;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiConstant\Members;
use VGirol\JsonApiAssert\Messages;

/**
 * Assertions relating to the jsonapi object
 */
trait AssertJsonapiObject
{
    /**
     * Asserts that a json fragment is a valid jsonapi object.
     *
     * It will do the following checks :
     * 1) asserts that the jsonapi object is not an array of objects (@see assertIsNotArrayOfObjects).
     * 2) asserts that the jsonapi object contains only the following allowed members : "version" and "meta"
     * (@see assertContainsOnlyAllowedMembers).
     *
     * Optionaly, if presents, it will checks :
     * 3) asserts that the version member is a string.
     * 4) asserts that meta member is valid (@see assertIsValidMetaObject).
     *
     * @param array   $json
     * @param boolean $strict If true, unsafe characters are not allowed when checking members name.
     *
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
            Members::JSONAPI_VERSION,
            Members::META
        ];
        static::assertContainsOnlyAllowedMembers(
            $allowed,
            $json
        );

        if (isset($json[Members::JSONAPI_VERSION])) {
            PHPUnit::assertIsString(
                $json[Members::JSONAPI_VERSION],
                Messages::JSONAPI_VERSION_IS_NOT_STRING
            );
        }

        if (isset($json[Members::META])) {
            static::assertIsValidMetaObject($json[Members::META], $strict);
        }
    }
}
