<?php

namespace VGirol\JsonApiAssert\Tests\Asserts\Content;

use VGirol\JsonApiAssert\Assert;
use VGirol\JsonApiAssert\Members;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiAssert\Tests\TestCase;

class PaginationTest extends TestCase
{
    /**
     * @test
     */
    public function hasPaginationLinks()
    {
        $json = [
            'self' => 'url',
            'first' => 'urlFirst',
            'last' => 'urlLast'
        ];

        Assert::assertHasPaginationLinks($json);
    }

    /**
     * @test
     */
    public function hasPaginationLinksFailed()
    {
        $json = [
            'self' => 'url'
        ];

        $this->setFailureException(
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
            'self' => 'url'
        ];

        Assert::assertHasNoPaginationLinks($json);
    }

    /**
     * @test
     */
    public function hasNoPaginationLinksFailed()
    {
        $json = [
            'self' => 'url',
            'first' => 'urlFirst'
        ];

        $this->setFailureException(
            sprintf(Messages::NOT_HAS_MEMBER, 'first')
        );

        Assert::assertHasNoPaginationLinks($json);
    }

    /**
     * @test
     */
    public function paginationLinksEquals()
    {
        $expected = [
            'first' => 'urlFirst',
            'next' => true,
            'last' => false
        ];
        $json = [
            'self' => 'url',
            'first' => 'urlFirst',
            'next' => 'urlNext'
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
            'self' => 'url',
            'first' => 'urlFirst',
            'next' => 'urlNext',
            'last' => 'urlLast'
        ];

        $this->setFailureException($failureMsg);

        Assert::assertPaginationLinksEquals($expected, $json);
    }

    public function paginationLinksEqualsFailedProvider()
    {
        return [
            'has not expected member' => [
                [
                    'first' => 'urlFirst',
                    'next' => false,
                    'prev' => false,
                    'last' => 'urlLast'
                ],
                null
            ],
            'not has expected member' => [
                [
                    'first' => 'urlFirst',
                    'next' => 'urlNext',
                    'prev' => 'urlPrev',
                    'last' => 'urlLast'
                ],
                null
            ],
            'not same value' => [
                [
                    'first' => 'urlFirstError',
                    'next' => 'urlNext',
                    'prev' => false,
                    'last' => 'urlLast'
                ],
                null
            ]
        ];
    }
}
