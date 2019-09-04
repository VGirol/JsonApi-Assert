<?php

declare(strict_types=1);

namespace VGirol\JsonApiAssert\Asserts\Structure;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Members;
use VGirol\JsonApiAssert\Messages;

/**
 * Assertions relating to the resource linkage
 */
trait AssertResourceLinkage
{
    /**
     * Asserts that a json fragment is a valid resource linkage object.
     *
     * @param array|null    $json
     * @param boolean       $strict     If true, unsafe characters are not allowed when checking members name.
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertIsValidResourceLinkage($json, bool $strict): void
    {
        if ($json === null) {
            $json = [];
        }

        PHPUnit::assertIsArray(
            $json,
            Messages::RESOURCE_LINKAGE_NOT_ARRAY
        );

        if (!static::isArrayOfObjects($json)) {
            $json = [$json];
        }
        foreach ($json as $resource) {
            static::assertIsValidResourceIdentifierObject($resource, $strict);
        }
    }

    /**
     * Asserts that a json fragment is a valid resource identifier object.
     *
     * @param array     $resource
     * @param boolean   $strict         If true, unsafe characters are not allowed when checking members name.
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertIsValidResourceIdentifierObject($resource, bool $strict): void
    {
        PHPUnit::assertIsArray(
            $resource,
            Messages::RESOURCE_IDENTIFIER_IS_NOT_ARRAY
        );

        PHPUnit::assertArrayHasKey(
            Members::ID,
            $resource,
            Messages::RESOURCE_ID_MEMBER_IS_ABSENT
        );
        static::assertResourceIdMember($resource);

        PHPUnit::assertArrayHasKey(
            Members::TYPE,
            $resource,
            Messages::RESOURCE_TYPE_MEMBER_IS_ABSENT
        );
        static::assertResourceTypeMember($resource, $strict);

        $allowed = [
            Members::ID,
            Members::TYPE,
            Members::META
        ];
        static::assertContainsOnlyAllowedMembers($allowed, $resource);

        if (isset($resource[Members::META])) {
            static::assertIsValidMetaObject($resource[Members::META], $strict);
        }
    }
}
