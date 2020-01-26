<?php

namespace VGirol\JsonApiAssert\Tests\Asserts\Content;

use VGirol\JsonApiAssert\Assert;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiAssert\Tests\TestCase;
use VGirol\JsonApiConstant\Members;

class PaginationTest extends TestCase
{
    /**
     * @test
     */
    public function hasPaginationLinks()
    {
        $json = [
            Members::LINK_SELF => 'url',
            Members::LINK_PAGINATION_FIRST => 'urlFirst',
            Members::LINK_PAGINATION_LAST => 'urlLast'
        ];

        Assert::assertHasPaginationLinks($json);
    }

    /**
     * @test
     */
    public function hasPaginationLinksFailed()
    {
        $json = [
            Members::LINK_SELF => 'url'
        ];

        $this->setFailure(
            sprintf(
                Messages::CONTAINS_AT_LEAST_ONE,
                implode(
                    ', ',
                    [
                        Members::LINK_PAGINATION_FIRST,
                        Members::LINK_PAGINATION_LAST,
                        Members::LINK_PAGINATION_PREV,
                        Members::LINK_PAGINATION_NEXT
                    ]
                )
            )
        );

        Assert::assertHasPaginationLinks($json);
    }

    /**
     * @test
     */
    public function hasNoPaginationLinks()
    {
        $json = [
            Members::LINK_SELF => 'url'
        ];

        Assert::assertHasNoPaginationLinks($json);
    }

    /**
     * @test
     */
    public function hasNoPaginationLinksFailed()
    {
        $json = [
            Members::LINK_SELF => 'url',
            Members::LINK_PAGINATION_FIRST => 'urlFirst'
        ];

        $this->setFailure(
            sprintf(Messages::NOT_HAS_MEMBER, Members::LINK_PAGINATION_FIRST)
        );

        Assert::assertHasNoPaginationLinks($json);
    }

    /**
     * @test
     */
    public function paginationLinksEquals()
    {
        $expected = [
            Members::LINK_PAGINATION_FIRST => 'urlFirst',
            Members::LINK_PAGINATION_NEXT => true,
            Members::LINK_PAGINATION_LAST => false
        ];
        $json = [
            Members::LINK_SELF => 'url',
            Members::LINK_PAGINATION_FIRST => 'urlFirst',
            Members::LINK_PAGINATION_NEXT => 'urlNext'
        ];

        Assert::assertPaginationLinksEquals($expected, $json);
    }

    /**
     * @test
     */
    public function paginationLinksEqualsFailed()
    {
        $expected = [
            Members::LINK_PAGINATION_FIRST => 'urlFirst',
            Members::LINK_PAGINATION_NEXT => false,
            Members::LINK_PAGINATION_PREV => false,
            Members::LINK_PAGINATION_LAST => 'urlLast'
        ];
        $json = [
            Members::LINK_SELF => 'url',
            Members::LINK_PAGINATION_FIRST => 'urlFirst',
            Members::LINK_PAGINATION_NEXT => 'urlNext',
            Members::LINK_PAGINATION_LAST => 'urlLast'
        ];

        $this->setFailure(Messages::PAGINATION_LINKS_NOT_EQUAL);

        Assert::assertPaginationLinksEquals($expected, $json);
    }

    /**
     * @test
     */
    public function hasPaginationMeta()
    {
        $json = [
            'key' => 'value',
            Members::META_PAGINATION => [
                'key' => 'value'
            ]
        ];

        Assert::assertHasPaginationMeta($json);
    }

    /**
     * @test
     */
    public function hasPaginationMetaFailed()
    {
        $json = [
            'key' => 'value'
        ];

        $this->setFailure(
            sprintf(
                Messages::HAS_MEMBER,
                Members::META_PAGINATION
            )
        );

        Assert::assertHasPaginationMeta($json);
    }

    /**
     * @test
     */
    public function hasNoPaginationMeta()
    {
        $json = [
            'key' => 'value'
        ];

        Assert::assertHasNoPaginationMeta($json);
    }

    /**
     * @test
     */
    public function hasNoPaginationMetaFailed()
    {
        $json = [
            'key' => 'value',
            Members::META_PAGINATION => [
                'key' => 'value'
            ]
        ];

        $this->setFailure(
            sprintf(Messages::NOT_HAS_MEMBER, Members::META_PAGINATION)
        );

        Assert::assertHasNoPaginationMEta($json);
    }

    /**
     * @test
     */
    public function paginationMetaEquals()
    {
        $expected = [
            'key' => 'value',
            Members::META_PAGINATION => [
                'key' => 'value'
            ]
        ];
        $json = [
            'key' => 'value',
            Members::META_PAGINATION => [
                'key' => 'value'
            ]
        ];

        Assert::assertPaginationMetaEquals($expected, $json);
    }

    /**
     * @test
     */
    public function paginationMetaEqualsFailed()
    {
        $expected = [
            'key' => 'value',
            Members::META_PAGINATION => [
                'key' => 'value'
            ]
        ];
        $json = [
            'key' => 'value',
            Members::META_PAGINATION => [
                'key' => 'not equal'
            ]
        ];

        $this->setFailure(Messages::PAGINATION_META_NOT_EQUAL);

        Assert::assertPaginationMetaEquals($expected, $json);
    }
}
