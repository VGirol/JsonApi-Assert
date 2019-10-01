<?php

declare(strict_types=1);

namespace VGirol\JsonApiAssert\Asserts\Structure;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiConstant\Members;
use VGirol\JsonApiAssert\Messages;

/**
 * Assertions relating to the errors object
 */
trait AssertErrorsObject
{
    /**
     * Asserts that a json fragment is a valid errors object.
     *
     * It will do the following checks :
     * 1) asserts that the errors object is an array of objects (@see assertIsArrayOfObjects).
     * 2) asserts that each error object of the collection is valid (@see assertIsValidErrorObject).
     *
     * @param array   $json
     * @param boolean $strict If true, unsafe characters are not allowed when checking members name.
     *
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
     * It will do the following checks :
     * 1) asserts that the error object is not empty.
     * 2) asserts it contains only the following allowed members :
     * "id", "links", "status", "code", "title", "details", "source", "meta" (@see assertContainsOnlyAllowedMembers).
     *
     * Optionaly, if presents, it will checks :
     * 3) asserts that the "status" member is a string.
     * 4) asserts that the "code" member is a string.
     * 5) asserts that the "title" member is a string.
     * 6) asserts that the "details" member is a string.
     * 7) asserts that the "source" member is valid(@see assertIsValidErrorSourceObject).
     * 8) asserts that the "links" member is valid(@see assertIsValidErrorLinksObject).
     * 9) asserts that the "meta" member is valid(@see assertIsValidMetaObject).
     *
     * @param array   $json
     * @param boolean $strict If true, unsafe characters are not allowed when checking members name.
     *
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
            Members::ERROR_STATUS,
            Members::ERROR_CODE,
            Members::ERROR_TITLE,
            Members::ERROR_DETAILS,
            Members::ERROR_SOURCE,
            Members::META
        ];
        static::assertContainsOnlyAllowedMembers($allowed, $json);

        $checks = [
            Members::ERROR_STATUS => Messages::ERROR_STATUS_IS_NOT_STRING,
            Members::ERROR_CODE => Messages::ERROR_CODE_IS_NOT_STRING,
            Members::ERROR_TITLE => Messages::ERROR_TITLE_IS_NOT_STRING,
            Members::ERROR_DETAILS => Messages::ERROR_DETAILS_IS_NOT_STRING
        ];

        foreach ($checks as $member => $failureMsg) {
            if (isset($json[$member])) {
                PHPUnit::assertIsString($json[$member], $failureMsg);
            }
        }

        if (isset($json[Members::ERROR_SOURCE])) {
            static::assertIsValidErrorSourceObject($json[Members::ERROR_SOURCE]);
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
     * It will do the following checks :
     * 1) asserts that le links object is valid (@see assertIsValidLinksObject with only "about" member allowed).
     *
     * @param array   $json
     * @param boolean $strict If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertIsValidErrorLinksObject($json, bool $strict): void
    {
        $allowed = [Members::LINK_ABOUT];
        static::assertIsValidLinksObject($json, $allowed, $strict);
    }

    /**
     * Asserts that a json fragment is a valid error source object.
     *
     * It will do the following checks :
     * 1) if the "pointer" member is present, asserts it is a string starting with a "/" character.
     * 2) if the "parameter" member is present, asserts that it is a string.
     *
     * @param array $json
     *
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertIsValidErrorSourceObject($json): void
    {
        PHPUnit::assertIsArray(
            $json,
            Messages::ERROR_SOURCE_OBJECT_NOT_ARRAY
        );

        if (isset($json[Members::ERROR_POINTER])) {
            PHPUnit::assertIsString(
                $json[Members::ERROR_POINTER],
                Messages::ERROR_SOURCE_POINTER_IS_NOT_STRING
            );
            PHPUnit::assertStringStartsWith(
                '/',
                $json[Members::ERROR_POINTER],
                Messages::ERROR_SOURCE_POINTER_START
            );
        }

        if (isset($json[Members::ERROR_PARAMETER])) {
            PHPUnit::assertIsString(
                $json[Members::ERROR_PARAMETER],
                Messages::ERROR_SOURCE_PARAMETER_IS_NOT_STRING
            );
        }
    }
}
