<?php

namespace VGirol\JsonApiAssert\Tests\Asserts\Structure;

use VGirol\JsonApiAssert\Assert as JsonApiAssert;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiAssert\Tests\TestCase;
use VGirol\JsonApiConstant\Members;

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
                    Members::LINK_SELF => 'url'
                ],
                false,
                false
            ],
            'with pagination' => [
                [
                    Members::LINK_SELF => 'url',
                    Members::LINK_PAGINATION_FIRST => 'url',
                    Members::LINK_PAGINATION_PREV => null,
                    Members::LINK_PAGINATION_NEXT => null,
                    Members::LINK_PAGINATION_LAST => 'url'
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
                    Members::LINK_SELF => 'url',
                    'anything' => 'not allowed'
                ],
                false,
                false,
                Messages::ONLY_ALLOWED_MEMBERS
            ],
            'with pagination and not allowed member' => [
                [
                    Members::LINK_SELF => 'url',
                    Members::LINK_PAGINATION_FIRST => 'url',
                    Members::LINK_PAGINATION_LAST => 'url',
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
                    Members::DATA => null
                ],
                false
            ],
            'to one relationship' => [
                [
                    Members::DATA => [
                        Members::TYPE => 'author',
                        Members::ID => '2'
                    ],
                    Members::LINKS => [
                        Members::LINK_SELF => 'http://example.com/articles/1/relationships/author',
                        Members::LINK_RELATED => 'http://example.com/articles/1/author',
                    ],
                    Members::META => [
                        'anything' => 'valid'
                    ]
                ],
                false
            ],
            'empty to many relationship' => [
                [
                    Members::DATA => []
                ],
                false
            ],
            'to many relationship' => [
                [
                    Members::DATA => [
                        [
                            Members::TYPE => 'author',
                            Members::ID => '2'
                        ],
                        [
                            Members::TYPE => 'author',
                            Members::ID => '3'
                        ]
                    ],
                    Members::LINKS => [
                        Members::LINK_SELF => 'http://example.com/articles/1/relationships/author',
                        Members::LINK_RELATED => 'http://example.com/articles/1/author',
                        Members::LINK_PAGINATION_FIRST => 'url',
                        Members::LINK_PAGINATION_NEXT => 'url'
                    ],
                    Members::META => [
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
                sprintf(Messages::CONTAINS_AT_LEAST_ONE, implode(', ', [Members::LINKS, Members::DATA, Members::META]))
            ],
            'meta is not valid' => [
                [
                    Members::DATA => [
                        Members::TYPE => 'test',
                        Members::ID => '2'
                    ],
                    Members::META => [
                        'key+' => 'not valid'
                    ]
                ],
                false,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ],
            'links is not valid' => [
                [
                    Members::DATA => [
                        Members::TYPE => 'test',
                        Members::ID => '2'
                    ],
                    Members::LINKS => 'not valid'
                ],
                false,
                Messages::LINKS_OBJECT_NOT_ARRAY
            ],
            'single resource with pagination' => [
                [
                    Members::DATA => [
                        Members::TYPE => 'test',
                        Members::ID => '2'
                    ],
                    Members::LINKS => [
                        Members::LINK_SELF => 'url',
                        Members::LINK_PAGINATION_FIRST => 'url',
                        Members::LINK_PAGINATION_LAST => 'url'
                    ]
                ],
                false,
                Messages::ONLY_ALLOWED_MEMBERS
            ],
            'array of resource identifier objects without pagination' => [
                [
                    [
                        Members::DATA => [
                            [
                                Members::TYPE => 'test',
                                Members::ID => '2'
                            ],
                            [
                                Members::TYPE => 'test',
                                Members::ID => '3'
                            ]
                        ],
                        Members::LINKS => [
                            Members::LINK_SELF => 'url',
                            Members::LINK_RELATED => 'url'
                        ]
                    ]
                ],
                false,
                sprintf(Messages::CONTAINS_AT_LEAST_ONE, implode(', ', [Members::LINKS, Members::DATA, Members::META]))
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
                Members::LINKS => [
                    Members::LINK_SELF => 'http://example.com/articles/1/relationships/author',
                    Members::LINK_RELATED => 'http://example.com/articles/1/author'
                ],
                Members::DATA => [
                    Members::TYPE => 'people',
                    Members::ID => '9'
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
                        Members::DATA => [
                            Members::TYPE => 'people',
                            Members::ID => '9'
                        ]
                    ]
                ],
                false,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ],
            'no safe member name' => [
                [
                    'author not safe' => [
                        Members::DATA => [
                            Members::TYPE => 'people',
                            Members::ID => '9'
                        ]
                    ]
                ],
                true,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ]
        ];
    }
}
