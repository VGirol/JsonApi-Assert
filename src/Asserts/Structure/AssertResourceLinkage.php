<?php

declare(strict_types=1);

namespace VGirol\JsonApiAssert\Asserts\Structure;

/**
 * Assertions relating to the resource linkage
 */
trait AssertResourceLinkage
{
    /**
     * Asserts that a json fragment is a valid resource linkage object.
     *
     * It will do the following checks :
     * 1) asserts that the provided resource linkage is either an object, an array of objects or the `null` value.
     * 2) asserts that the resource linkage or the collection of resource linkage is valid
     * (@see assertIsValidResourceIdentifierObject).
     *
     * @param array|null $json
     * @param boolean    $strict If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public static function assertIsValidResourceLinkage($json, bool $strict): void
    {
        static::askService('validateResourceLinkage', $json, $strict);
    }

    /**
     * Asserts that a json fragment is a valid resource identifier object.
     *
     * It will do the following checks :
     * 1) asserts that the resource as "id" (@see assertResourceIdMember)
     * and "type" (@see assertResourceTypeMember) members.
     * 2) asserts that it contains only the following allowed members : "id", "type" and "meta"
     * (@see assertContainsOnlyAllowedMembers).
     *
     * Optionaly, if presents, it will checks :
     * 3) asserts that the meta object is valid (@see assertIsValidMetaObject).
     *
     * @param array   $resource
     * @param boolean $strict   If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public static function assertIsValidResourceIdentifierObject($resource, bool $strict): void
    {
        static::askService('validateResourceIdentifierObject', $resource, $strict);
    }
}
