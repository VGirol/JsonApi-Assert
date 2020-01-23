<?php

declare(strict_types=1);

namespace VGirol\JsonApiAssert\Asserts\Structure;

/**
 * Assertions relating to the attributes object
 */
trait AssertAttributesObject
{
    /**
     * Asserts that a json fragment is a valid attributes object.
     *
     * It will do the following checks :
     * 1) asserts that attributes object is not an array of objects (@see assertIsNotArrayOfObjects).
     * 2) asserts that attributes object has no member with forbidden name (@see assertFieldHasNoForbiddenMemberName).
     * 3) asserts that each member name of the attributes object is valid (@see assertIsValidMemberName).
     *
     * @param array   $json
     * @param boolean $strict If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertIsValidAttributesObject($json, bool $strict): void
    {
        static::askService('validateAttributesObject', $json, $strict);
    }

    /**
     * Asserts that a field object has no forbidden member name.
     *
     * Asserts that a field object (i.e., a resource object’s attributes or one of its relationships)
     * has no forbidden member name.
     *
     * It will do the following checks :
     * 1) asserts that each member name of the field is not a forbidden name (@see assertIsNotForbiddenMemberName).
     * 2) if the field has nested objects, it will checks each all.
     *
     * @param mixed $field
     *
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertFieldHasNoForbiddenMemberName($field): void
    {
        static::askService('fieldHasNoForbiddenMemberName', $field);
    }

    /**
     * Asserts that a member name is not forbidden (like "relationships" or "links").
     *
     * @param string $name
     *
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertIsNotForbiddenMemberName($name): void
    {
        static::askService('isNotForbiddenMemberName', $name);
    }
}
