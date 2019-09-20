<?php

declare(strict_types=1);

namespace VGirol\JsonApiAssert\Asserts\Structure;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Members;
use VGirol\JsonApiAssert\Messages;

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
        static::assertIsNotArrayOfObjects(
            $json,
            Messages::ATTRIBUTES_OBJECT_IS_NOT_ARRAY
        );

        static::assertFieldHasNoForbiddenMemberName($json);

        foreach (array_keys($json) as $key) {
            static::assertIsValidMemberName($key, $strict);
        }
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
        if (!is_array($field)) {
            return;
        }

        foreach ($field as $key => $value) {
            // For objects, $key is a string
            // For arrays of objects, $key is an integer
            if (is_string($key)) {
                static::assertIsNotForbiddenMemberName($key);
            }
            static::assertFieldHasNoForbiddenMemberName($value);
        }
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
        if (!\is_string($name)) {
            static::invalidArgument(1, 'string', $name);
        }

        $forbidden = [
            Members::RELATIONSHIPS,
            Members::LINKS
        ];
        PHPUnit::assertNotContains(
            $name,
            $forbidden,
            Messages::MEMBER_NAME_NOT_ALLOWED
        );
    }
}
