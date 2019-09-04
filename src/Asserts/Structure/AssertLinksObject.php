<?php

declare(strict_types=1);

namespace VGirol\JsonApiAssert\Asserts\Structure;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Members;
use VGirol\JsonApiAssert\Messages;

/**
 * Assertions relating to the links object
 */
trait AssertLinksObject
{
    /**
     * Asserts that a json fragment is a valid links object.
     *
     * @param array         $json
     * @param array<string> $allowedMembers
     * @param boolean       $strict         If true, unsafe characters are not allowed when checking members name.
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertIsValidLinksObject($json, array $allowedMembers, bool $strict): void
    {
        PHPUnit::assertIsArray(
            $json,
            Messages::LINKS_OBJECT_NOT_ARRAY
        );

        static::assertContainsOnlyAllowedMembers(
            $allowedMembers,
            $json
        );

        foreach ($json as $link) {
            static::assertIsValidLinkObject($link, $strict);
        }
    }

    /**
     * Asserts that a json fragment is a valid link object.
     *
     * @param array     $json
     * @param boolean   $strict         If true, unsafe characters are not allowed when checking members name.
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertIsValidLinkObject($json, bool $strict): void
    {
        if (!\is_array($json)) {
            if ($json === null) {
                $json = '';
            }
            PHPUnit::assertIsString(
                $json,
                Messages::LINK_OBJECT_IS_NOT_ARRAY
            );
            return;
        }

        PHPUnit::assertArrayHasKey(
            Members::HREF,
            $json,
            Messages::LINK_OBJECT_MISS_HREF_MEMBER
        );

        $allowed = [
            Members::HREF,
            Members::META
        ];
        static::assertContainsOnlyAllowedMembers(
            $allowed,
            $json
        );

        if (isset($json[Members::META])) {
            static::assertIsValidMetaObject($json[Members::META], $strict);
        }
    }
}
