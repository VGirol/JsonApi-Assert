<?php
declare(strict_types = 1);

namespace VGirol\JsonApiAssert\Asserts\Structure;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Members;
use VGirol\JsonApiAssert\Messages;

/**
 * Assertions relating to the errors object
 */
trait AssertErrorsObject
{
    /**
     * Asserts that a json fragment is a valid errors object.
     *
     * @param array     $json
     * @param boolean   $strict         If true, unsafe characters are not allowed when checking members name.
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertIsValidErrorsObject($json, bool $strict): void
    {
        static::assertIsArrayOfObjects(
            $json,
            Messages::ERRORS_OBJECT_NOT_ARRAY
        );

        foreach ($json as $error) {
            static::assertIsValidErrorObject($error, $strict);
        }
    }

    /**
     * Asserts that a json fragment is a valid error object.
     *
     * @param array     $json
     * @param boolean   $strict         If true, unsafe characters are not allowed when checking members name.
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertIsValidErrorObject($json, bool $strict): void
    {
        PHPUnit::assertIsArray(
            $json,
            Messages::ERROR_OBJECT_NOT_ARRAY
        );

        PHPUnit::assertNotEmpty(
            $json,
            Messages::ERROR_OBJECT_NOT_EMPTY
        );

        $allowed = [
            Members::ID,
            Members::LINKS,
            Members::STATUS,
            Members::CODE,
            Members::TITLE,
            Members::DETAILS,
            Members::SOURCE,
            Members::META
        ];
        static::assertContainsOnlyAllowedMembers($allowed, $json);

        $checks = [
            Members::STATUS => Messages::ERROR_STATUS_IS_NOT_STRING,
            Members::CODE => Messages::ERROR_CODE_IS_NOT_STRING,
            Members::TITLE => Messages::ERROR_TITLE_IS_NOT_STRING,
            Members::DETAILS => Messages::ERROR_DETAILS_IS_NOT_STRING
        ];

        foreach ($checks as $member => $failureMsg) {
            if (isset($json[$member])) {
                PHPUnit::assertIsString($json[$member], $failureMsg);
            }
        }

        if (isset($json[Members::SOURCE])) {
            static::assertIsValidErrorSourceObject($json[Members::SOURCE]);
        }

        if (isset($json[Members::LINKS])) {
            static::assertIsValidErrorLinksObject($json[Members::LINKS], $strict);
        }

        if (isset($json[Members::META])) {
            static::assertIsValidMetaObject($json[Members::META], $strict);
        }
    }

    /**
     * Asserts that a json fragment is a valid error links object.
     *
     * @param array     $json
     * @param boolean   $strict         If true, unsafe characters are not allowed when checking members name.
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertIsValidErrorLinksObject($json, bool $strict): void
    {
        $allowed = [Members::ABOUT];
        static::assertIsValidLinksObject($json, $allowed, $strict);
    }

    /**
     * Asserts that a json fragment is a valid error source object.
     *
     * @param array $json
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertIsValidErrorSourceObject($json): void
    {
        PHPUnit::assertIsArray(
            $json,
            Messages::ERROR_SOURCE_OBJECT_NOT_ARRAY
        );

        if (isset($json[Members::POINTER])) {
            PHPUnit::assertIsString(
                $json[Members::POINTER],
                Messages::ERROR_SOURCE_POINTER_IS_NOT_STRING
            );
            PHPUnit::assertStringStartsWith(
                '/',
                $json[Members::POINTER],
                Messages::ERROR_SOURCE_POINTER_START
            );
        }

        if (isset($json[Members::PARAMETER])) {
            PHPUnit::assertIsString(
                $json[Members::PARAMETER],
                Messages::ERROR_SOURCE_PARAMETER_IS_NOT_STRING
            );
        }
    }
}
