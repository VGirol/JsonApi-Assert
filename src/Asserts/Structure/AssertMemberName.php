<?php

declare(strict_types=1);

namespace VGirol\JsonApiAssert\Asserts\Structure;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Constraint\MemberNameConstraint;

/**
 * Assertions relating to the member name
 */
trait AssertMemberName
{
    /**
     * Asserts that a member name is valid.
     *
     * It will do the following checks :
     * 1) asserts that the name is a string with at least one character.
     * 2) asserts that the name has only allowed characters.
     * 3) asserts that it starts and ends with a globally allowed character.
     *
     * @link https://jsonapi.org/format/#document-member-names-allowed-characters
     *
     * @param string  $name
     * @param boolean $strict If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertIsValidMemberName($name, bool $strict): void
    {
        PHPUnit::assertThat($name, self::memberNameConstraint($strict));
    }

    /**
     * Returns a new instance of the \VGirol\JsonApiAssert\Constraint\MemberNameConstraint class.
     *
     * @param bool $strict
     *
     * @return \VGirol\JsonApiAssert\Constraint\MemberNameConstraint
     */
    private static function memberNameConstraint(bool $strict): MemberNameConstraint
    {
        return new MemberNameConstraint($strict);
    }
}
