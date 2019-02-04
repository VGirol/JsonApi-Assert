<?php
namespace VGirol\JsonApi\Tests\Unit\Check;

use PHPUnit\Framework\Assert as PHPUnit;

trait JsonApiLinksObjectTest
{
    /**
     * @dataProvider validLinkObjectProvider
     */
    public function testValidLinkObject($data)
    {
        $this->checkLinkObject($data);
    }

    public function validLinkObjectProvider()
    {
        return [
            'null value' => [
                null
            ],
            'as string' => [
                'validLink'
            ],
            'as object' => [
                [
                    'href' => 'validLink',
                    'meta' => [
                        'key' => 'value'
                    ]
                ]
            ]
        ];
    }

    /**
     * @dataProvider notValidLinkObjectProvider
     */
    public function testNotValidLinkObject($data, $failureMessage)
    {
        $fn = function ($data) {
            $this->checkLinkObject($data);
        };

        $this->assertTestFail($fn, $failureMessage, $data);
    }

    public function notValidLinkObjectProvider()
    {
        return [
            'not an array' => [
                666,
                null
            ],
            'no "href" member' => [
                [
                    'meta' => 'error'
                ],
                static::$JSONAPI_ERROR_LINK_OBJECT_MISS_HREF_MEMBER
            ],
            'not only allowed members' => [
                [
                    'href' => 'valid',
                    'meta' => 'valid',
                    'test' => 'error'
                ],
                null
            ]
        ];
    }

    /**
     * @dataProvider validLinksObjectProvider
     */
    public function testValidLinksObject($data, $withPagination, $forError)
    {
        $this->checkLinksObject($data, $withPagination, $forError);
    }

    public function validLinksObjectProvider()
    {
        return [
            'short' => [
                [
                    'self' => 'url'
                ],
                false,
                false
            ],
            'with pagination' => [
                [
                    'self' => 'url',
                    'related' => 'url',
                    'first' => 'url',
                    'last' => 'url',
                    'next' => 'url',
                    'prev' => 'url'
                ],
                true,
                false
            ],
            'for error' => [
                [
                    'about' => 'url',
                ],
                false,
                true
            ]
        ];
    }

    /**
     * @dataProvider notValidLinksObjectProvider
     */
    public function testNotValidLinksObject($data, $withPagination, $forError, $failureMessage)
    {
        $fn = function ($data, $withPagination, $forError) {
            $this->checkLinksObject($data, $withPagination, $forError);
        };

        $this->assertTestFail($fn, $failureMessage, $data, $withPagination, $forError);
    }

    public function notValidLinksObjectProvider()
    {
        return [
            'not an array' => [
                'error',
                false,
                false,
                static::$JSONAPI_ERROR_LINKS_OBJECT_NOT_ARRAY
            ],
            'pagination not allowed' => [
                [
                    'self' => 'valid',
                    'first' => 'valid',
                ],
                false,
                false,
                null
            ],
            'not only allowed members' => [
                [
                    'self' => 'valid',
                    'first' => 'valid',
                    'test' => 'error'
                ],
                true,
                false,
                null
            ],
            'link not valid' => [
                [
                    'self' => 666
                ],
                false,
                false,
                null
            ],
            'link error' => [
                [
                    'error' => 'not about member'
                ],
                false,
                true,
                null
            ]
        ];
    }
}
