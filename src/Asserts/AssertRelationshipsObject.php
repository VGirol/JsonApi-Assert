<?php
namespace VGirol\JsonApiAssert\Asserts;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Messages;
use PHPUnit\Framework\ExpectationFailedException;

trait AssertRelationshipsObject
{
    /**
     * Asserts that a relationships object is valid.
     *
     * @param array $relationships
     * 
     * @throws PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertIsValidRelationshipsObject($relationships)
    {
        static::assertIsNotArrayOfObjects($relationships);

        foreach ($relationships as $key => $relationship) {
            static::assertIsValidMemberName($key);
            static::assertIsValidRelationshipObject($relationship);
        }
    }

    /**
     * Asserts that a relationship object is valid.
     *
     * @param array $relationship
     * 
     * @throws PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertIsValidRelationshipObject($relationship)
    {
        $expected = ['links', 'data', 'meta'];
        static::assertContainsAtLeastOneMember($expected, $relationship);

        $withPagination = false;
        if (isset($relationship['data'])) {
            $data = $relationship['data'];
            static::assertIsValidResourceLinkage($data);
            $withPagination = static::isToManyResourceLinkage($data);
        }

        if (isset($relationship['links'])) {
            $links = $relationship['links'];
            static::assertIsValidRelationshipLinksObject($links, $withPagination);
        }

        if (isset($relationship['meta'])) {
            static::assertIsValidMetaObject($relationship['meta']);
        }
    }

    /**
     * Asserts that a link object extracted from a relationship object is valid.
     *
     * @param array     $data
     * @param boolean   $withPagination
     * 
     * @throws PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertIsValidRelationshipLinksObject($data, $withPagination)
    {
        $allowed = ['self', 'related'];
        if ($withPagination) {
            $allowed = array_merge($allowed, ['first', 'last', 'next', 'prev']);
        }
        static::assertIsValidLinksObject($data, $allowed);
    }

    /**
     * Asserts that a resource linkage object is valid.
     *
     * @param array $data
     * 
     * @throws PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertIsValidResourceLinkage($data)
    {
        try {
            PHPUnit::assertIsArray(
                $data,
                Messages::RESOURCE_LINKAGE_NOT_ARRAY
            );
            try {
                PHPUnit::assertNotEmpty($data);
            } catch (ExpectationFailedException $e) {
                return;
            }
        } catch (ExpectationFailedException $e) {
            PHPUnit::assertNull(
                $data,
                Messages::RESOURCE_LINKAGE_NOT_ARRAY
            );
            return;
        }

        if (static::isArrayOfObjects($data)) {
            foreach ($data as $resource) {
                static::assertIsValidResourceIdentifierObject($resource);
            }
        } else {
            static::assertIsValidResourceIdentifierObject($data);
        }
    }

    private static function isToManyResourceLinkage($data)
    {
        if (is_null($data)) {
            return false;
        }

        if (!is_array($data)) {
            return false;
        }

        if (empty($data)) {
            return false;
        }

        return static::isArrayOfObjects($data);
    }
}
