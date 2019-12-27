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
     * @dataProvider paginationLinksEqualsFailedProvider
     */
    public function paginationLinksEqualsFailed($expected, $failureMsg)
    {
        $json = [
            Members::LINK_SELF => 'url',
            Members::LINK_PAGINATION_FIRST => 'urlFirst',
            Members::LINK_PAGINATION_NEXT => 'urlNext',
            Members::LINK_PAGINATION_LAST => 'urlLast'
        ];

        $this->setFailure($failureMsg);

        Assert::assertPaginationLinksEquals($expected, $json);
    }

    public function paginationLinksEqualsFailedProvider()
    {
        return [
            'has too many members' => [
                [
                    Members::LINK_PAGINATION_FIRST => 'urlFirst',
                    Members::LINK_PAGINATION_NEXT => false,
                    Members::LINK_PAGINATION_PREV => false,
                    Members::LINK_PAGINATION_LAST => 'urlLast'
                ],
                Messages::PAGINATION_LINKS_NOT_EQUAL
            ],
            'has not all expected member' => [
                [
                    Members::LINK_PAGINATION_FIRST => 'urlFirst',
                    Members::LINK_PAGINATION_NEXT => 'urlNext',
                    Members::LINK_PAGINATION_PREV => 'urlPrev',
                    Members::LINK_PAGINATION_LAST => 'urlLast'
                ],
                Messages::PAGINATION_LINKS_NOT_EQUAL
            ],
            'not same value' => [
                [
                    Members::LINK_PAGINATION_FIRST => 'urlFirstError',
                    Members::LINK_PAGINATION_NEXT => 'urlNext',
                    Members::LINK_PAGINATION_PREV => false,
                    Members::LINK_PAGINATION_LAST => 'urlLast'
                ],
                Messages::PAGINATION_LINKS_NOT_EQUAL
            ]
        ];
    }
}
