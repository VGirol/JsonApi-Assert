<?php

declare(strict_types=1);

namespace VGirol\JsonApiAssert\Asserts\Content;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Constraint\LinkEqualsConstraint;
use VGirol\JsonApiAssert\Messages;

/**
 * This trait adds the ability to test links objects.
 */
trait AssertLinks
{
    /**
     * Asserts that a links object equals an expected links array.
     *
     * It will do the following checks :
     * 1) asserts that the links array length is equal to the expected links array length.
     * 2) asserts that each expected link is present in the links array.
     *
     * @link https://jsonapi.org/format/#document-links
     *
     * @param array $expected An expected links array
     * @param array $links    A links object to inspect
     *
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public static function assertLinksObjectEquals($expected, $links): void
    {
        $countExpected = count($expected);
        $count = count($links);
        PHPUnit::assertEquals(
            $countExpected,
            $count,
            sprintf(Messages::LINKS_OBJECT_HAVE_NOT_SAME_LENGTH, $count, $countExpected)
        );

        foreach ($expected as $name => $value) {
            static::assertLinksObjectContains($name, $value, $links);
        }
    }

    /**
     * Asserts that a links object contains an expected link.
     *
     * It will do the following checks :
     * 1) asserts that the links array length is equal to the expected links array length.
     * 2) asserts that each expected link is present in the links array.
     *
     * @link https://jsonapi.org/format/#document-links
     *
     * @param string            $name     The name of the link
     * @param array|string|null $expected The expected link value
     * @param array             $links    The links object to inspect
     *
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public static function assertLinksObjectContains($name, $expected, $links): void
    {
        static::assertHasMember($name, $links);
        static::assertLinkObjectEquals($expected, $links[$name]);
    }

    /**
     * Asserts that a link object equals an expected value.
     *
     * @link https://jsonapi.org/format/#document-links
     *
     * @param array|string|null $expected The expected link value
     * @param array|string|null $link     The link to test
     *
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public static function assertLinkObjectEquals($expected, $link): void
    {
        PHPUnit::assertThat($link, self::linkEqualsConstraint($expected));
    }

    /**
     * Returns a new instance of the \VGirol\JsonApiAssert\Constraint\LinkEqualsConstraint class.
     *
     * @param array|string|null $expected The expected link
     *
     * @return \VGirol\JsonApiAssert\Constraint\LinkEqualsConstraint
     */
    private static function linkEqualsConstraint($expected): LinkEqualsConstraint
    {
        return new LinkEqualsConstraint($expected);
    }
}
