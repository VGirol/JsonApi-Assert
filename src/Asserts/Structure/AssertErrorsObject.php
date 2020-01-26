<?php

declare(strict_types=1);

namespace VGirol\JsonApiAssert\Asserts\Structure;

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
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public static function assertIsValidErrorsObject($json, bool $strict): void
    {
        static::askService('validateErrorsObject', $json, $strict);
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
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public static function assertIsValidErrorObject($json, bool $strict): void
    {
        static::askService('validateErrorObject', $json, $strict);
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
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public static function assertIsValidErrorLinksObject($json, bool $strict): void
    {
        static::askService('validateErrorLinksObject', $json, $strict);
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
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public static function assertIsValidErrorSourceObject($json): void
    {
        static::askService('validateErrorSourceObject', $json);
    }
}
