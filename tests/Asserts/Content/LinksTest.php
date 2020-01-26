<?php

namespace VGirol\JsonApiAssert\Tests\Asserts\Content;

use VGirol\JsonApiAssert\Assert;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiAssert\Tests\TestCase;
use VGirol\JsonApiConstant\Members;

class LinksTest extends TestCase
{
    /**
     * @test
     */
    public function linkObjectEquals()
    {
        $expected = 'url';
        $link = [
            Members::LINK_HREF => 'url'
        ];
        Assert::assertLinkObjectEquals($expected, $link);
    }

    /**
     * @test
     */
    public function linkObjectEqualsFailed()
    {
        $expected = null;
        $link = 'not null';

        $this->setFailure($this->formatAsRegex('Failed asserting that %s equals %s.'));

        Assert::assertLinkObjectEquals($expected, $link);
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
