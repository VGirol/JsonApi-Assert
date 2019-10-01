<?php

declare(strict_types=1);

namespace VGirol\JsonApiAssert\Asserts\Structure;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiConstant\Members;
use VGirol\JsonApiAssert\Messages;

/**
 * Assertions relating to the links object
 */
trait AssertLinksObject
{
    /**
     * Asserts that a json fragment is a valid links object.
     *
     * It will do the following checks :
     * 1) asserts that it contains only allowed members (@see assertContainsOnlyAllowedMembers).
     * 2) asserts that each member of the links object is a valid link object (@see assertIsValidLinkObject).
     *
     * @param array         $json
     * @param array<string> $allowedMembers
     * @param boolean       $strict         If true, unsafe characters are not allowed when checking members name.
     *
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
     * It will do the following checks :
     * 1) asserts that the link object is a string, an array or the `null` value.
     * 2) in case it is an array :
     *      3) asserts that it has the "href" member.
     *      4) asserts that it contains only the following allowed members : "href" and "meta"
     *       (@see assertContainsOnlyAllowedMembers).
     *      5) if present, asserts that the "meta" object is valid (@see assertIsValidMetaObject).
     *
     * @param array|string|null $json
     * @param boolean           $strict If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertIsValidLinkObject($json, bool $strict): void
    {
        if ($json === null) {
            PHPUnit::assertNull($json);
            return;
        }

        if (!\is_array($json)) {
            PHPUnit::assertIsString(
                $json,
                Messages::LINK_OBJECT_IS_NOT_ARRAY
            );
            return;
        }

        PHPUnit::assertArrayHasKey(
            Members::LINK_HREF,
            $json,
            Messages::LINK_OBJECT_MISS_HREF_MEMBER
        );

        $allowed = [
            Members::LINK_HREF,
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
