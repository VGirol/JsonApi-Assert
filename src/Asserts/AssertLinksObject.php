<?php
namespace VGirol\JsonApiAssert\Asserts;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Messages;
use PHPUnit\Framework\ExpectationFailedException;

trait AssertLinksObject
{
    public static function assertIsValidLinksObject($links, $allowedMembers)
    {
        PHPUnit::assertIsArray(
            $links,
            Messages::LINKS_OBJECT_NOT_ARRAY
        );

        static::assertContainsOnlyAllowedMembers(
            $allowedMembers,
            $links
        );

        foreach ($links as $key => $link) {
            static::assertIsValidLinkObject($link);
        }
    }

    public static function assertIsValidLinkObject($link)
    {
        try {
            PHPUnit::assertIsArray($link);
        } catch (ExpectationFailedException $e) {
            try {
                PHPUnit::assertIsString($link);
                return;
            } catch (ExpectationFailedException $e) {
                PHPUnit::assertNull($link);
                return;
            }
        }

        PHPUnit::assertArrayHasKey(
            'href',
            $link,
            Messages::LINK_OBJECT_MISS_HREF_MEMBER
        );

        $allowed = ['href', 'meta'];
        static::assertContainsOnlyAllowedMembers(
            $allowed,
            $link
        );

        if (isset($link['meta'])) {
            static::assertIsValidMetaObject($link['meta']);
        }
    }
}
