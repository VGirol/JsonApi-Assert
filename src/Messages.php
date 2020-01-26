<?php

declare(strict_types=1);

namespace VGirol\JsonApiAssert;

use VGirol\JsonApiStructure\Messages as JsonApiStructureMessages;

/**
 * All the messages
 */
abstract class Messages extends JsonApiStructureMessages
{
    const ERRORS_OBJECT_CONTAINS_NOT_ENOUGH_ERRORS =
    'Errors array must be greater or equal than the expected errors array.';
    const ERRORS_OBJECT_DOES_NOT_CONTAIN_EXPECTED_ERROR =
    'The "errors" member does not contain the expected error %s.';
    const RESOURCE_COLLECTION_HAVE_NOT_SAME_LENGTH =
    'Failed asserting that the resource collection length (%u) is equal to %u.';
    const RESOURCE_IS_NOT_EQUAL =
    'Failed asserting that the resource %s is equal to %s.';
    const PAGINATION_LINKS_NOT_EQUAL =
    'Failed asserting that pagination links equal expected values.';
    const PAGINATION_META_NOT_EQUAL =
    'Failed asserting that pagination meta equal expected values.';
    const RESOURCE_LINKAGE_COLLECTION_MUST_BE_EMPTY =
    'Failed asserting that the resource linkage collection is empty.';
    const RESOURCE_LINKAGE_COLLECTION_HAVE_NOT_SAME_LENGTH =
    'Failed asserting that the resource linkage collection length (%u) is equal to %u.';
    const RESOURCE_IDENTIFIER_IS_NOT_EQUAL =
    'Failed asserting that the resource identifier %s is equal to %s.';
    const RESOURCE_LINKAGE_MUST_BE_NULL =
    'Failed asserting that the resource linkage %s is null.';
    const RESOURCE_LINKAGE_MUST_NOT_BE_NULL =
    'Failed asserting that the resource linkage is not null.';
    const LINKS_OBJECT_HAVE_NOT_SAME_LENGTH =
    'Failed asserting that the links collection length (%u) is equal to %u.';
    const JSONAPI_OBJECT_NOT_EQUAL =
    'Failed asserting that the "jsonapi" object %s is equal to %s.';
}
