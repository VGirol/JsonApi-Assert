<?php
namespace VGirol\JsonApiAssert\Tests\Asserts\Structure;

use VGirol\JsonApiAssert\Assert as JsonApiAssert;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiAssert\Tests\TestCase;
use VGirol\JsonApiConstant\Members;

class LinksObjectTest extends TestCase
{
    /**
     * @test
     * @dataProvider validLinkObjectProvider
     */
    public function linkObjectIsValid($data, $strict)
    {
        JsonApiAssert::assertIsValidLinkObject($data, $strict);
    }

    public function validLinkObjectProvider()
    {
        return [
            'null value' => [
                null,
                false
            ],
            'as string' => [
                'validLink',
                false
            ],
            'as object' => [
                [
                    Members::LINK_HREF => 'validLink',
                    Members::META => [
                        'key' => 'value'
                    ]
                ],
                true
            ]
        ];
    }

    /**
     * @test
     * @dataProvider notValidLinkObjectProvider
     */
    public function linkObjectIsNotValid($data, $strict, $failureMessage)
    {
        $this->setFailure($failureMessage);
        JsonApiAssert::assertIsValidLinkObject($data, $strict);
    }

    public function notValidLinkObjectProvider()
    {
        return [
            'not an array' => [
                666,
                false,
                Messages::LINK_OBJECT_IS_NOT_ARRAY
            ],
            'no "href" member' => [
                [
                    Members::META => 'error'
                ],
                false,
                Messages::LINK_OBJECT_MISS_HREF_MEMBER
            ],
            'not only allowed members' => [
                [
                    Members::LINK_HREF => 'valid',
                    Members::META => 'valid',
                    'test' => 'error'
                ],
                false,
                Messages::ONLY_ALLOWED_MEMBERS
            ],
            'meta not valid' => [
                [
                    Members::LINK_HREF => 'valid',
                    Members::META => [
                        'key+' => 'not valid'
                    ]
                ],
                false,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ],
            'meta not safe' => [
                [
                    Members::LINK_HREF => 'valid',
                    Members::META => [
                        'not safe' => 'because of blank character'
                    ]
                ],
                true,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ]
        ];
    }

    /**
     * @test
     */
    public function linksObjectIsValid()
    {
        $data = [
            Members::LINK_SELF => 'url',
            Members::LINK_RELATED => 'url'
        ];
        $allowed = [Members::LINK_SELF, Members::LINK_RELATED];
        $strict = false;

        JsonApiAssert::assertIsValidLinksObject($data, $allowed, $strict);
    }

    /**
     * @test
     * @dataProvider notValidLinksObjectProvider
     */
    public function linksObjectIsNotValid($data, $allowed, $strict, $failureMessage)
    {
        $this->setFailure($failureMessage);
        JsonApiAssert::assertIsValidLinksObject($data, $allowed, $strict);
    }

    public function notValidLinksObjectProvider()
    {
        return [
            'not an array' => [
                'error',
                [Members::LINK_SELF, Members::LINK_RELATED],
                false,
                Messages::LINKS_OBJECT_NOT_ARRAY
            ],
            'not only allowed members' => [
                [
                    Members::LINK_SELF => 'valid',
                    Members::LINK_PAGINATION_FIRST => 'valid',
                    'test' => 'error'
                ],
                [Members::LINK_SELF, Members::LINK_RELATED],
                false,
                Messages::ONLY_ALLOWED_MEMBERS
            ],
            'link not valid' => [
                [
                    Members::LINK_SELF => 666
                ],
                [Members::LINK_SELF, Members::LINK_RELATED],
                false,
                Messages::LINK_OBJECT_IS_NOT_ARRAY
            ],
            'link has not safe meta member' => [
                [
                    Members::LINK_SELF => [
                        Members::LINK_HREF => 'url',
                        Members::META => [
                            'not safe' => 'because of blank character'
                        ]
                    ]
                ],
                [Members::LINK_SELF, Members::LINK_RELATED],
                true,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ]
        ];
    }
}
