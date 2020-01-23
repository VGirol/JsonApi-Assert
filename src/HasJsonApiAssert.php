<?php

declare(strict_types=1);

namespace VGirol\JsonApiAssert;

use PHPUnit\Framework\Assert as PHPUnit;
use PHPUnit\Framework\ExpectationFailedException;
use VGirol\JsonApiAssert\Asserts\Content\AssertErrors;
use VGirol\JsonApiAssert\Asserts\Content\AssertInclude;
use VGirol\JsonApiAssert\Asserts\Content\AssertJsonapi;
use VGirol\JsonApiAssert\Asserts\Content\AssertLinks;
use VGirol\JsonApiAssert\Asserts\Content\AssertPagination;
use VGirol\JsonApiAssert\Asserts\Content\AssertResource;
use VGirol\JsonApiAssert\Asserts\Content\AssertResourceLinkage as AssertLinkage;
use VGirol\JsonApiAssert\Asserts\Structure\AssertArrays;
use VGirol\JsonApiAssert\Asserts\Structure\AssertAttributesObject;
use VGirol\JsonApiAssert\Asserts\Structure\AssertErrorsObject;
use VGirol\JsonApiAssert\Asserts\Structure\AssertJsonapiObject;
use VGirol\JsonApiAssert\Asserts\Structure\AssertLinksObject;
use VGirol\JsonApiAssert\Asserts\Structure\AssertMemberName;
use VGirol\JsonApiAssert\Asserts\Structure\AssertMembers;
use VGirol\JsonApiAssert\Asserts\Structure\AssertMetaObject;
use VGirol\JsonApiAssert\Asserts\Structure\AssertRelationshipsObject;
use VGirol\JsonApiAssert\Asserts\Structure\AssertResourceLinkage;
use VGirol\JsonApiAssert\Asserts\Structure\AssertResourceObject;
use VGirol\JsonApiAssert\Asserts\Structure\AssertStructure;
use VGirol\JsonApiStructure\Exception\CanThrowInvalidArgumentException;
use VGirol\JsonApiStructure\Exception\ValidationException;
use VGirol\JsonApiStructure\ValidateService;

/**
 * This trait provide a set of assertions to test documents using the JSON:API specification.
 */
trait HasJsonApiAssert
{
    use CanThrowInvalidArgumentException;

    // Structure
    use AssertArrays;
    use AssertAttributesObject;
    use AssertErrorsObject;
    use AssertJsonapiObject;
    use AssertLinksObject;
    use AssertMemberName;
    use AssertMembers;
    use AssertMetaObject;
    use AssertRelationshipsObject;
    use AssertResourceLinkage;
    use AssertResourceObject;
    use AssertStructure;

    // Content
    use AssertLinks;
    use AssertJsonapi;
    use AssertPagination;
    use AssertResource;
    use AssertLinkage;
    use AssertErrors;
    use AssertInclude;

    /**
     * Undocumented variable
     *
     * @var ValidateService
     */
    private static $service;

    /**
     * Undocumented function
     *
     * @return ValidateService
     */
    protected static function getServiceInstance(): ValidateService
    {
        if (static::$service === null) {
            static::$service = new ValidateService();
        }

        return static::$service;
    }

    protected static function askService(string $method, ...$args): void
    {
        try {
            static::proxyService($method, ...$args);
            static::succeed();
        } catch (ValidationException $e) {
            throw new ExpectationFailedException($e->getMessage(), null, $e);
        }
    }

    protected static function proxyService(string $method, ...$args)
    {
        $service = static::getServiceInstance();

        return \call_user_func([$service, $method], ...$args);
    }

    protected static function succeed(string $message = ''): void
    {
        PHPUnit::assertTrue(true, $message);
    }
}
