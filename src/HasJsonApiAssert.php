<?php
declare (strict_types = 1);

namespace VGirol\JsonApiAssert;

use VGirol\JsonApiAssert\Asserts\Content\AssertErrors;
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

/**
 * This trait provide a set of assertions to test documents using the JSON:API specification.
 */
trait HasJsonApiAssert
{
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

    /**
     * Throws an InvalidArgumentException because of an invalid argument passed to a method.
     *
     * @param integer $argument
     * @param string $type
     * @param mixed $value
     * @return void
     * @throws \VGirol\JsonApiAssert\InvalidArgumentException
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected static function invalidArgument(int $argument, string $type, $value = null): void
    {
        throw InvalidArgumentHelper::factory($argument, $type, $value);
    }
}
