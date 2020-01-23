<?php

declare(strict_types=1);

namespace VGirol\JsonApiAssert\Asserts\Structure;

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
        static::askService('validateJsonapiObject', $json, $strict);
    }
}
