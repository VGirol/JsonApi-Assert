<?php
namespace VGirol\JsonApiAssert\Asserts;

/**
 * Assertions relating to the relationships object
 */
trait AssertRelationshipsObject
{
    /**
     * Asserts that a json fragment is a valid relationships object.
     *
     * @param array     $json
     * @param boolean   $strict         If true, unsafe characters are not allowed when checking members name.
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertIsValidRelationshipsObject($json, bool $strict): void
    {
        static::assertIsNotArrayOfObjects($json);

        foreach ($json as $key => $relationship) {
            static::assertIsValidMemberName($key, $strict);
            static::assertIsValidRelationshipObject($relationship, $strict);
        }
    }

    /**
     * Asserts that a json fragment is a valid relationship object.
     *
     * @param array     $json
     * @param boolean   $strict         If true, unsafe characters are not allowed when checking members name.
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertIsValidRelationshipObject($json, bool $strict): void
    {
        $expected = ['links', 'data', 'meta'];
        static::assertContainsAtLeastOneMember($expected, $json);

        if (isset($json['data'])) {
            $data = $json['data'];
            static::assertIsValidResourceLinkage($data, $strict);
        }

        if (isset($json['links'])) {
            $links = $json['links'];
            $withPagination = isset($json['data']) && static::isArrayOfObjects($json['data']);
            static::assertIsValidRelationshipLinksObject($links, $withPagination, $strict);
        }

        if (isset($json['meta'])) {
            static::assertIsValidMetaObject($json['meta'], $strict);
        }
    }

    /**
     * Asserts that a json fragment is a valid link object extracted from a relationship object.
     *
     * @param array     $json
     * @param boolean   $withPagination
     * @param boolean   $strict         If true, unsafe characters are not allowed when checking members name.
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertIsValidRelationshipLinksObject($json, bool $withPagination, bool $strict): void
    {
        $allowed = ['self', 'related'];
        if ($withPagination) {
            $allowed = array_merge($allowed, ['first', 'last', 'next', 'prev']);
        }
        static::assertIsValidLinksObject($json, $allowed, $strict);
    }
}
