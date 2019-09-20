<?php

declare(strict_types=1);

namespace VGirol\JsonApiAssert;

/**
 * All the member's names
 */
abstract class Members
{
    const ATTRIBUTES = 'attributes';
    const DATA = 'data';
    const ERROR_CODE = 'code';
    const ERROR_DETAILS = 'details';
    const ERROR_PARAMETER = 'parameter';
    const ERROR_POINTER = 'pointer';
    const ERROR_SOURCE = 'source';
    const ERROR_STATUS = 'status';
    const ERROR_TITLE = 'title';
    const ERRORS = 'errors';
    const ID = 'id';
    const INCLUDED = 'included';
    const JSONAPI = 'jsonapi';
    const JSONAPI_VERSION = 'version';
    const LINK_ABOUT = 'about';
    const LINK_HREF = 'href';
    const LINK_PAGINATION_FIRST = 'first';
    const LINK_PAGINATION_LAST = 'last';
    const LINK_PAGINATION_NEXT = 'next';
    const LINK_PAGINATION_PREV = 'prev';
    const LINK_RELATED = 'related';
    const LINK_SELF = 'self';
    const LINKS = 'links';
    const META = 'meta';
    const META_PAGINATION = 'pagination';
    const RELATIONSHIPS = 'relationships';
    const TYPE = 'type';
}
