<?php
declare(strict_types = 1);

namespace VGirol\JsonApiAssert\Asserts\Structure;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Members;

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
     * @param array    $json
     * @param boolean       $strict         If true, unsafe characters are not allowed when checking members name.
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertIsValidRelationshipObject($json, bool $strict): void
    {
        PHPUnit::assertIsArray($json);

        static::assertContainsAtLeastOneMember(
            [
                Members::LINKS,
                Members::DATA,
                Members::META
            ],
            $json
        );

        if (isset($json[Members::DATA])) {
            $data = $json[Members::DATA];
            static::assertIsValidResourceLinkage($data, $strict);
        }

        if (isset($json[Members::LINKS])) {
            $links = $json[Members::LINKS];
            $withPagination = isset($json[Members::DATA]) && static::isArrayOfObjects($json[Members::DATA]);
            static::assertIsValidRelationshipLinksObject($links, $withPagination, $strict);
        }

        if (isset($json[Members::META])) {
            static::assertIsValidMetaObject($json[Members::META], $strict);
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
        $allowed = [
            Members::SELF,
            Members::RELATED
        ];
        if ($withPagination) {
            $allowed = array_merge(
                $allowed,
                [
                    Members::FIRST,
                    Members::LAST,
                    Members::NEXT,
                    Members::PREV
                ]
            );
        }
        static::assertIsValidLinksObject($json, $allowed, $strict);
    }
}
