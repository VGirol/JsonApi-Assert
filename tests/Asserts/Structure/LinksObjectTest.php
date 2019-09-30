<?php
namespace VGirol\JsonApiAssert\Tests\Asserts\Structure;

use VGirol\JsonApiAssert\Assert as JsonApiAssert;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiAssert\Tests\TestCase;

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
                    'href' => 'validLink',
                    'meta' => [
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
                    'meta' => 'error'
                ],
                false,
                Messages::LINK_OBJECT_MISS_HREF_MEMBER
            ],
            'not only allowed members' => [
                [
                    'href' => 'valid',
                    'meta' => 'valid',
                    'test' => 'error'
                ],
                false,
                Messages::ONLY_ALLOWED_MEMBERS
            ],
            'meta not valid' => [
                [
                    'href' => 'valid',
                    'meta' => [
                        'key+' => 'not valid'
                    ]
                ],
                false,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ],
            'meta not safe' => [
                [
                    'href' => 'valid',
                    'meta' => [
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
            'self' => 'url',
            'related' => 'url'
        ];
        $allowed = ['self', 'related'];
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
                ['self', 'related'],
                false,
                Messages::LINKS_OBJECT_NOT_ARRAY
            ],
            'not only allowed members' => [
                [
                    'self' => 'valid',
                    'first' => 'valid',
                    'test' => 'error'
                ],
                ['self', 'related'],
                false,
                Messages::ONLY_ALLOWED_MEMBERS
            ],
            'link not valid' => [
                [
                    'self' => 666
                ],
                ['self', 'related'],
                false,
                Messages::LINK_OBJECT_IS_NOT_ARRAY
            ],
            'link has not safe meta member' => [
                [
                    'self' => [
                        'href' => 'url',
                        'meta' => [
                            'not safe' => 'because of blank character'
                        ]
                    ]
                ],
                ['self', 'related'],
                true,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ]
        ];
    }
}
