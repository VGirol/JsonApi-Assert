<?php

namespace VGirol\JsonApiAssert\Tests\Constraints;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Constraint\LinkEqualsConstraint;
use VGirol\JsonApiAssert\Tests\TestCase;
use VGirol\JsonApiConstant\Members;

class LinkEqualsConstraintTest extends TestCase
{
    /**
     * @test
     */
    public function constraintMessage()
    {
        $expected = 'url';
        $obj = new LinkEqualsConstraint($expected);

        $string = $obj->toString();
        PHPUnit::assertEquals(
            "equals '{$expected}'",
            $string
        );
    }

    /**
     * @test
     * @dataProvider constraintMatchesProvider
     */
    public function constraintMatches($expected, $link)
    {
        $obj = new LinkEqualsConstraint($expected);

        PHPUnit::assertTrue($obj->check($link));
    }

    public function constraintMatchesProvider()
    {
        return [
            'null' => [
                null,
                null
            ],
            'string' => [
                'url',
                'url'
            ],
            'with query string' => [
                'url?query1=test&query2=anything',
                'url?query1=test&query2=anything'
            ],
            'array' => [
                'url',
                [
                    Members::LINK_HREF => 'url',
                    Members::META => [
                        'key' => 'value'
                    ]
                ]
            ]
        ];
    }

    /**
     * @test
     * @dataProvider constraintMatchesFailedProvider
     */
    public function constraintMatchesFailed($expected, $link)
    {
        $obj = new LinkEqualsConstraint($expected);

        PHPUnit::assertFalse($obj->check($link));
    }

    public function constraintMatchesFailedProvider()
    {
        return [
            'must be null' => [
                null,
                'not null'
            ],
            'must not be null' => [
                'url',
                null
            ],
            'must have query string' => [
                'url?query=test',
                'url'
            ],
            'must not have query string' => [
                'url',
                'url?query=test'
            ],
            'not same url' => [
                'url1',
                'url2'
            ],
            'not same count of query strings' => [
                'url?query1=test',
                'url?query1=test&query2=anything'
            ],
            'not same query strings' => [
                'url?query1=test',
                'url?query1=anything'
            ]
        ];
    }
}
