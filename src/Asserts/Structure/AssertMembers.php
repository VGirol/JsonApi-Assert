<?php
declare(strict_types = 1);

namespace VGirol\JsonApiAssert\Asserts\Structure;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Constraint\ContainsAtLeastOneConstraint;
use VGirol\JsonApiAssert\Constraint\ContainsOnlyAllowedMembersConstraint;
use VGirol\JsonApiAssert\Members;
use VGirol\JsonApiAssert\Messages;

/**
 * Assertions relating to the object's members
 */
trait AssertMembers
{
    /**
     * Asserts that a json object has an expected member.
     *
     * @param string    $expected
     * @param array     $json
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\Exception
     */
    public static function assertHasMember($expected, $json): void
    {
        if (!\is_string($expected)) {
            static::invalidArgument(1, 'string', $expected);
        }
        if (!\is_array($json)) {
            static::invalidArgument(2, 'array', $json);
        }
        PHPUnit::assertArrayHasKey($expected, $json, sprintf(Messages::HAS_MEMBER, $expected));
    }

    /**
     * Asserts that a json object has expected members.
     *
     * @param array<string> $expected
     * @param array         $json
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\Exception
     */
    public static function assertHasMembers($expected, $json): void
    {
        if (!\is_array($expected)) {
            static::invalidArgument(1, 'array', $expected);
        }
        if (!\is_array($json)) {
            static::invalidArgument(2, 'array', $json);
        }
        foreach ($expected as $key) {
            PHPUnit::assertArrayHasKey($key, $json, sprintf(Messages::HAS_MEMBER, $key));
        }
    }

    /**
     * Asserts that a json object has only expected members.
     *
     * @param array<string> $expected
     * @param array         $json
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\Exception
     */
    public static function assertHasOnlyMembers($expected, $json): void
    {
        if (!\is_array($expected)) {
            static::invalidArgument(1, 'array', $expected);
        }
        if (!\is_array($json)) {
            static::invalidArgument(2, 'array', $json);
        }

        PHPUnit::assertEquals(
            $expected,
            array_keys($json),
            sprintf(Messages::HAS_ONLY_MEMBERS, implode(', ', $expected))
        );
    }

    /**
     * Asserts that a json object not has an unexpected member.
     *
     * @param string    $expected
     * @param array     $json
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\Exception
     */
    public static function assertNotHasMember($expected, $json): void
    {
        if (!\is_string($expected)) {
            static::invalidArgument(1, 'string', $expected);
        }
        if (!\is_array($json)) {
            static::invalidArgument(2, 'array', $json);
        }
        PHPUnit::assertArrayNotHasKey($expected, $json, sprintf(Messages::NOT_HAS_MEMBER, $expected));
    }

    /**
     * Asserts that a json object not has unexpected members.
     *
     * @param array<string> $expected
     * @param array         $json
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\Exception
     */
    public static function assertNotHasMembers($expected, $json): void
    {
        if (!\is_array($expected)) {
            static::invalidArgument(1, 'array', $expected);
        }

        foreach ($expected as $key) {
            static::assertNotHasMember($key, $json);
        }
    }

    /**
     * Asserts that a json object has a "data" member.
     *
     * @param array     $json
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertHasData($json): void
    {
        static::assertHasMember(Members::DATA, $json);
    }

    /**
     * Asserts that a json object has an "attributes" member.
     *
     * @param array     $json
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertHasAttributes($json): void
    {
        static::assertHasMember(Members::ATTRIBUTES, $json);
    }

    /**
     * Asserts that a json object has a "links" member.
     *
     * @param array     $json
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertHasLinks($json): void
    {
        static::assertHasMember(Members::LINKS, $json);
    }

    /**
     * Asserts that a json object has a "meta" member.
     *
     * @param array     $json
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertHasMeta($json): void
    {
        static::assertHasMember(Members::META, $json);
    }

    /**
     * Asserts that a json object has an "included" member.
     *
     * @param array     $json
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertHasIncluded($json): void
    {
        static::assertHasMember(Members::INCLUDED, $json);
    }

    /**
     * Asserts that a json object has a "relationships" member.
     *
     * @param array     $json
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertHasRelationships($json): void
    {
        static::assertHasMember(Members::RELATIONSHIPS, $json);
    }

    /**
     * Asserts that a json object has an "errors" member.
     *
     * @param array     $json
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertHasErrors($json): void
    {
        static::assertHasMember(Members::ERRORS, $json);
    }

    /**
     * Asserts that a json object has a "jsonapi" member.
     *
     * @param array     $json
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertHasJsonapi($json): void
    {
        static::assertHasMember(Members::JSONAPI, $json);
    }

    /**
     * Asserts that a json object contains at least one member from the list provided.
     *
     * @param array<string> $expected   The expected members
     * @param array $json       The json object
     * @param string $message   An optional message to explain why the test failed
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertContainsAtLeastOneMember($expected, $json, string $message = ''): void
    {
        PHPUnit::assertThat($json, self::containsAtLeastOneMemberConstraint($expected), $message);
    }

    /**
     * Returns a new instance of the \VGirol\JsonApiAssert\Constraint\ContainsAtLeastOneConstraint class.
     *
     * @param array $expected   The expected members
     * @return \VGirol\JsonApiAssert\Constraint\ContainsAtLeastOneConstraint
     */
    private static function containsAtLeastOneMemberConstraint($expected): ContainsAtLeastOneConstraint
    {
        return new ContainsAtLeastOneConstraint($expected);
    }

    /**
     * Check if a json object contains at least one member from the list provided.
     *
     * @param array $expected   The expected members
     * @param array $json       The json object
     * @return boolean
     */
    private static function containsAtLeastOneMember($expected, $json): bool
    {
        $constraint = static::containsAtLeastOneMemberConstraint($expected);

        return $constraint->check($json);
    }

    /**
     * Asserts that a json object contains only members from the provided list.
     *
     * @param array<string> $expected   The expected members
     * @param array $json       The json object
     * @param string $message   An optional message to explain why the test failed
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertContainsOnlyAllowedMembers($expected, $json, string $message = ''): void
    {
        $message = Messages::ONLY_ALLOWED_MEMBERS . "\n" . $message;
        PHPUnit::assertThat($json, self::containsOnlyAllowedMembersConstraint($expected), $message);
    }

    /**
     * Returns a new instance of the \VGirol\JsonApiAssert\Constraint\ContainsOnlyAllowedMembersConstraint class.
     *
     * @param array $expected   The expected members
     * @return \VGirol\JsonApiAssert\Constraint\ContainsOnlyAllowedMembersConstraint
     */
    private static function containsOnlyAllowedMembersConstraint($expected): ContainsOnlyAllowedMembersConstraint
    {
        return new ContainsOnlyAllowedMembersConstraint($expected);
    }
}
