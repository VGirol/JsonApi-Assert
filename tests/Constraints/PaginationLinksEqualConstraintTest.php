<?php

namespace VGirol\JsonApiAssert\Tests\Constraints;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Constraint\PaginationLinksEqualConstraint;
use VGirol\JsonApiAssert\Tests\TestCase;
use VGirol\JsonApiConstant\Members;

class PaginationLinksEqualConstraintTest extends TestCase
{
    /**
     * @test
     */
    public function constraintMessage()
    {
        $expected = [
            Members::LINK_PAGINATION_FIRST => 'first_url'
        ];
        $allowed = [
            Members::LINK_PAGINATION_FIRST,
            Members::LINK_PAGINATION_LAST,
            Members::LINK_PAGINATION_NEXT,
            Members::LINK_PAGINATION_PREV
        ];
        $obj = new PaginationLinksEqualConstraint($expected, $allowed);

        $string = $obj->toString();
        PHPUnit::assertMatchesRegularExpression(
            '/equals .*/',
            $string
        );
    }

    /**
     * @test
     */
    public function constraintMatches()
    {
        $allowed = [
            Members::LINK_PAGINATION_FIRST,
            Members::LINK_PAGINATION_LAST,
            Members::LINK_PAGINATION_NEXT,
            Members::LINK_PAGINATION_PREV
        ];
        $expected = [
            Members::LINK_PAGINATION_FIRST => 'urlFirst',
            Members::LINK_PAGINATION_NEXT => true,
            Members::LINK_PAGINATION_LAST => false
        ];
        $links = [
            Members::LINK_SELF => 'url',
            Members::LINK_PAGINATION_FIRST => 'urlFirst',
            Members::LINK_PAGINATION_NEXT => 'urlNext'
        ];
        $obj = new PaginationLinksEqualConstraint($expected, $allowed);

        PHPUnit::assertTrue($obj->check($links));
    }

    /**
     * @test
     * @dataProvider constraintMatchesFailedProvider
     */
    public function constraintMatchesFailed($expected)
    {
        $allowed = [
            Members::LINK_PAGINATION_FIRST,
            Members::LINK_PAGINATION_LAST,
            Members::LINK_PAGINATION_NEXT,
            Members::LINK_PAGINATION_PREV
        ];
        $links = [
            Members::LINK_SELF => 'url',
            Members::LINK_PAGINATION_FIRST => 'urlFirst',
            Members::LINK_PAGINATION_NEXT => 'urlNext',
            Members::LINK_PAGINATION_LAST => 'urlLast'
        ];
        $obj = new PaginationLinksEqualConstraint($expected, $allowed);

        PHPUnit::assertFalse($obj->check($links));
    }

    public function constraintMatchesFailedProvider()
    {
        return [
            'has too many members' => [
                [
                    Members::LINK_PAGINATION_FIRST => 'urlFirst',
                    Members::LINK_PAGINATION_NEXT => false,
                    Members::LINK_PAGINATION_PREV => false,
                    Members::LINK_PAGINATION_LAST => 'urlLast'
                ]
            ],
            'has not all expected member' => [
                [
                    Members::LINK_PAGINATION_FIRST => 'urlFirst',
                    Members::LINK_PAGINATION_NEXT => 'urlNext',
                    Members::LINK_PAGINATION_PREV => 'urlPrev',
                    Members::LINK_PAGINATION_LAST => 'urlLast'
                ]
            ],
            'not same value' => [
                [
                    Members::LINK_PAGINATION_FIRST => 'urlFirstError',
                    Members::LINK_PAGINATION_NEXT => 'urlNext',
                    Members::LINK_PAGINATION_PREV => false,
                    Members::LINK_PAGINATION_LAST => 'urlLast'
                ]
            ]
        ];
    }
}
