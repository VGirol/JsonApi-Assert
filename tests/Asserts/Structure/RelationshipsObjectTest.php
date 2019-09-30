<?php

namespace VGirol\JsonApiAssert\Tests\Asserts\Structure;

use VGirol\JsonApiAssert\Assert as JsonApiAssert;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiAssert\Tests\TestCase;

class RelationshipsObjectTest extends TestCase
{
    /**
     * @test
     * @dataProvider validRelationshipLinksObjectProvider
     */
    public function relationshipLinksObjectIsValid($json, $withPagination, $strict)
    {
        JsonApiAssert::assertIsValidRelationshipLinksObject($json, $withPagination, $strict);
    }

    public function validRelationshipLinksObjectProvider()
    {
        return [
            'without pagination' => [
                [
                    'self' => 'url'
                ],
                false,
                false
            ],
            'with pagination' => [
                [
                    'self' => 'url',
                    'first' => 'url',
                    'prev' => null,
                    'next' => null,
                    'last' => 'url'
                ],
                true,
                false
            ]
        ];
    }

    /**
     * @test
     * @dataProvider notValidRelationshipLinksObjectProvider
     */
    public function relationshipLinksObjectIsNotValid($json, $withPagination, $strict, $failureMessage)
    {
        $this->setFailure($failureMessage);
        JsonApiAssert::assertIsValidRelationshipLinksObject($json, $withPagination, $strict);
    }

    public function notValidRelationshipLinksObjectProvider()
    {
        return [
            'not allowed member' => [
                [
                    'self' => 'url',
                    'anything' => 'not allowed'
                ],
                false,
                false,
                Messages::ONLY_ALLOWED_MEMBERS
            ],
            'with pagination and not allowed member' => [
                [
                    'self' => 'url',
                    'first' => 'url',
                    'last' => 'url',
                    'anything' => 'not allowed'
                ],
                true,
                false,
                Messages::ONLY_ALLOWED_MEMBERS
            ]
        ];
    }

    /**
     * @test
     * @dataProvider validRelationshipObjectProvider
     */
    public function relationshipObjectIsValid($data, $strict)
    {
        JsonApiAssert::assertIsValidRelationshipObject($data, $strict);
    }

    public function validRelationshipObjectProvider()
    {
        return [
            'empty to one relationship' => [
                [
                    'data' => null
                ],
                false
            ],
            'to one relationship' => [
                [
                    'data' => [
                        'type' => 'author',
                        'id' => '2'
                    ],
                    'links' => [
                        'self' => 'http://example.com/articles/1/relationships/author',
                        'related' => 'http://example.com/articles/1/author',
                    ],
                    'meta' => [
                        'anything' => 'valid'
                    ]
                ],
                false
            ],
            'empty to many relationship' => [
                [
                    'data' => []
                ],
                false
            ],
            'to many relationship' => [
                [
                    'data' => [
                        [
                            'type' => 'author',
                            'id' => '2'
                        ],
                        [
                            'type' => 'author',
                            'id' => '3'
                        ]
                    ],
                    'links' => [
                        'self' => 'http://example.com/articles/1/relationships/author',
                        'related' => 'http://example.com/articles/1/author',
                        'first' => 'url',
                        'next' => 'url'
                    ],
                    'meta' => [
                        'anything' => 'valid'
                    ]
                ],
                false
            ]
        ];
    }

    /**
     * @test
     * @dataProvider notValidRelationshipObjectProvider
     */
    public function relationshipObjectIsNotValid($data, $strict, $failureMessage)
    {
        $this->setFailure($failureMessage);
        JsonApiAssert::assertIsValidRelationshipObject($data, $strict);
    }

    public function notValidRelationshipObjectProvider()
    {
        return [
            'not an array' => [
                'not valid',
                false,
                sprintf('\'%s\' is of type "array".', 'not valid')
            ],
            'mandatory member miss' => [
                [
                    'anything' => [
                        'not' => 'valid'
                    ]
                ],
                false,
                sprintf(Messages::CONTAINS_AT_LEAST_ONE, implode(', ', ['links', 'data', 'meta']))
            ],
            'meta is not valid' => [
                [
                    'data' => [
                        'type' => 'test',
                        'id' => '2'
                    ],
                    'meta' => [
                        'key+' => 'not valid'
                    ]
                ],
                false,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ],
            'links is not valid' => [
                [
                    'data' => [
                        'type' => 'test',
                        'id' => '2'
                    ],
                    'links' => 'not valid'
                ],
                false,
                Messages::LINKS_OBJECT_NOT_ARRAY
            ],
            'single resource with pagination' => [
                [
                    'data' => [
                        'type' => 'test',
                        'id' => '2'
                    ],
                    'links' => [
                        'self' => 'url',
                        'first' => 'url',
                        'last' => 'url'
                    ]
                ],
                false,
                Messages::ONLY_ALLOWED_MEMBERS
            ],
            'array of resource identifier objects without pagination' => [
                [
                    [
                        'data' => [
                            [
                                'type' => 'test',
                                'id' => '2'
                            ],
                            [
                                'type' => 'test',
                                'id' => '3'
                            ]
                        ],
                        'links' => [
                            'self' => 'url',
                            'related' => 'url'
                        ]
                    ]
                ],
                false,
                sprintf(Messages::CONTAINS_AT_LEAST_ONE, implode(', ', ['links', 'data', 'meta']))
            ]
        ];
    }

    /**
     * @test
     */
    public function relationshipsObjectIsValid()
    {
        $data = [
            'author' => [
                'links' => [
                    'self' => 'http://example.com/articles/1/relationships/author',
                    'related' => 'http://example.com/articles/1/author'
                ],
                'data' => [
                    'type' => 'people',
                    'id' => '9'
                ]
            ]
        ];
        $strict = false;

        JsonApiAssert::assertIsValidRelationshipsObject($data, $strict);
    }

    /**
     * @test
     * @dataProvider notValidRelationshipsObjectProvider
     */
    public function relationshipsObjectIsNotValid($data, $strict, $failureMessage)
    {
        $this->setFailure($failureMessage);
        JsonApiAssert::assertIsValidRelationshipsObject($data, $strict);
    }

    public function notValidRelationshipsObjectProvider()
    {
        return [
            'an array of objects' => [
                [
                    ['test' => 'not valid'],
                    ['anything' => 'not valid']
                ],
                false,
                Messages::MUST_NOT_BE_ARRAY_OF_OBJECTS
            ],
            'no valid member name' => [
                [
                    'author+' => [
                        'data' => [
                            'type' => 'people',
                            'id' => '9'
                        ]
                    ]
                ],
                false,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ],
            'no safe member name' => [
                [
                    'author not safe' => [
                        'data' => [
                            'type' => 'people',
                            'id' => '9'
                        ]
                    ]
                ],
                true,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ]
        ];
    }
}
