<?php

declare(strict_types=1);

namespace VGirol\JsonApiAssert\Asserts\Content;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Constraint\PaginationLinksEqualConstraint;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiConstant\Members;

/**
 * This trait adds the ability to test pagination (links and meta).
 */
trait AssertPagination
{
    /**
     * Gets the list of allowed members for pagination links
     *
     * @return array
     */
    private static function allowedMembers(): array
    {
        return [
            Members::LINK_PAGINATION_FIRST,
            Members::LINK_PAGINATION_LAST,
            Members::LINK_PAGINATION_PREV,
            Members::LINK_PAGINATION_NEXT
        ];
    }

    /**
     * Asserts that a links object has pagination links.
     *
     * @link https://jsonapi.org/format/#document-links
     *
     * @param array $links A links object to inspect
     *
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertHasPaginationLinks($links): void
    {
        static::assertContainsAtLeastOneMember(
            static::allowedMembers(),
            $links
        );
    }

    /**
     * Asserts that a links object has no pagination links.
     *
     * @link https://jsonapi.org/format/#document-links
     *
     * @param array $links A links object to inspect
     *
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertHasNoPaginationLinks($links): void
    {
        foreach (static::allowedMembers() as $name) {
            static::assertNotHasMember($name, $links);
        }
    }

    /**
     * Asserts that a links object has the expected pagination links.
     *
     * @link https://jsonapi.org/format/#document-links
     *
     * @param array $expected The expected links object
     * @param array $json     The links object to test
     *
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertPaginationLinksEquals($expected, $json): void
    {
        PHPUnit::assertThat(
            $json,
            self::paginationLinksEqualConstraint($expected),
            Messages::PAGINATION_LINKS_NOT_EQUAL
        );
    }

    /**
     * Asserts that a meta object has a "pagination" member.
     *
     * @link https://jsonapi.org/format/#document-meta
     *
     * @param array $meta The meta object to inspect
     *
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertHasPaginationMeta($meta): void
    {
        static::assertHasMember(Members::META_PAGINATION, $meta);
    }

    /**
     * Asserts that a meta object has no "pagination" member.
     *
     * @link https://jsonapi.org/format/#document-meta
     *
     * @param array $meta The meta object to inspect
     *
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertHasNoPaginationMeta($meta): void
    {
        static::assertNotHasMember(Members::META_PAGINATION, $meta);
    }

    /**
     * Asserts that a meta object has the expected pagination member.
     *
     * @link https://jsonapi.org/format/#document-links
     *
     * @param array $expected The expected pagination meta object
     * @param array $json     The meta object to test
     *
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertPaginationMetaEquals($expected, $json): void
    {
        PHPUnit::assertEquals($expected, $json, Messages::PAGINATION_META_NOT_EQUAL);
    }

    /**
     * Returns a new instance of the \VGirol\JsonApiAssert\Constraint\PaginationLinksEqualConstraint class.
     *
     * @param array $expected The expected links
     *
     * @return \VGirol\JsonApiAssert\Constraint\PaginationLinksEqualConstraint
     */
    private static function paginationLinksEqualConstraint($expected): PaginationLinksEqualConstraint
    {
        return new PaginationLinksEqualConstraint($expected, static::allowedMembers());
    }
}
