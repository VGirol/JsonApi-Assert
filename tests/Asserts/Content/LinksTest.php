<?php

namespace VGirol\JsonApiAssert\Tests\Asserts\Content;

use VGirol\JsonApiAssert\Assert;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiAssert\Tests\TestCase;

class LinksTest extends TestCase
{
    /**
     * @test
     * @dataProvider linkObjectEqualsProvider
     */
    public function linkObjectEquals($expected, $link)
    {
        Assert::assertLinkObjectEquals($expected, $link);
    }

    public function linkObjectEqualsProvider()
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
            ]
        ];
    }

    /**
     * @test
     * @dataProvider linkObjectEqualsFailedProvider
     */
    public function linkObjectEqualsFailed($expected, $link)
    {
        $this->setFailure($this->formatAsRegex('Failed asserting that %s equals %s.'));

        Assert::assertLinkObjectEquals($expected, $link);
    }

    public function linkObjectEqualsFailedProvider()
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

    /**
     * @test
     */
    public function linksObjectContains()
    {
        $name = 'self';
        $expected = 'url';
        $links = [
            'self' => 'url',
            'another' => 'anything'
        ];
        Assert::assertLinksObjectContains($name, $expected, $links);
    }

    /**
     * @test
     * @dataProvider linksObjectContainsFailedProvider
     */
    public function linksObjectContainsFailed($name, $expected, $links, $failureMsg)
    {
        $this->setFailure($failureMsg);

        Assert::assertLinksObjectContains($name, $expected, $links);
    }

    public function linksObjectContainsFailedProvider()
    {
        return [
            'not has expected link member' => [
                'self',
                'url',
                [
                    'anything' => 'url'
                ],
                sprintf(Messages::HAS_MEMBER, 'self')
            ],
            'link is not as expected' => [
                'self',
                'url',
                [
                    'self' => 'url1'
                ],
                $this->formatAsRegex('Failed asserting that %s equals %s.')
            ]
        ];
    }

    /**
     * @test
     */
    public function linksObjectEquals()
    {
        $expected = [
            'self' => 'url'
        ];
        $links = [
            'self' => 'url'
        ];
        Assert::assertLinksObjectEquals($expected, $links);
    }

    /**
     * @test
     * @dataProvider linksObjectEqualsFailedProvider
     */
    public function linksObjectEqualsFailed($expected, $links, $failureMsg)
    {
        $this->setFailure($failureMsg);

        Assert::assertLinksObjectEquals($expected, $links);
    }

    public function linksObjectEqualsFailedProvider()
    {
        return [
            'not same count' => [
                [
                    'self' => 'url'
                ],
                [
                    'self' => 'url',
                    'anything' => 'url'
                ],
                $this->formatAsRegex(Messages::LINKS_OBJECT_HAVE_NOT_SAME_LENGTH)
            ],
            'link is not as expected' => [
                [
                    'self' => 'url'
                ],
                [
                    'self' => 'url2'
                ],
                $this->formatAsRegex('Failed asserting that %s equals %s.')
            ]
        ];
    }
}
