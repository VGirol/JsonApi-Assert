<?php
namespace VGirol\JsonApiAssert\Tests\Asserts\Structure;

use VGirol\JsonApiAssert\Assert as JsonApiAssert;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiAssert\Tests\TestCase;

class StructureTest extends TestCase
{
    /**
     * @test
     * @dataProvider validStructureProvider
     */
    public function documentHasValidStructure($data, $strict)
    {
        JsonApiAssert::assertHasValidStructure($data, $strict);
    }

    public function validStructureProvider()
    {
        return [
            'with data' => [
                [
                    'links' => [
                        'self' => 'http://example.com/articles',
                        'first' => 'url',
                        'last' => 'url'
                    ],
                    'data' => [
                        [
                            'type' => 'articles',
                            'id' => '1',
                            'attributes' => [
                                'title' => 'JSON:API paints my bikeshed!'
                            ]
                        ],
                        [
                            'type' => 'articles',
                            'id' => '2',
                            'attributes' => [
                                'title' => 'Rails is Omakase'
                            ],
                            'relationships' => [
                                'test' => [
                                    'data' => [
                                        'type' => 'relation',
                                        'id' => '12'
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'meta' => [
                        'anything' => 'valid'
                    ],
                    'included' => [
                        [
                            'type' => 'relation',
                            'id' => '12',
                            'attributes' => [
                                'anything' => 'valid'
                            ]
                        ]
                    ]
                ],
                false
            ],
            'with errors' => [
                [
                    'errors' => [
                        [
                            'code' => 'E13'
                        ],
                        [
                            'code' => 'E14'
                        ]
                    ],
                    'jsonapi' => [
                        'version' => 'valid'
                    ]
                ],
                false
            ]
        ];
    }

    /**
     * @test
     * @dataProvider notValidStructureProvider
     */
    public function documentHasNotValidStructure($data, $strict, $failureMessage)
    {
        $this->setFailure($failureMessage);
        JsonApiAssert::assertHasValidStructure($data, $strict);
    }

    public function notValidStructureProvider()
    {
        return [
            'no valid top-level member' => [
                [
                    'links' => [
                        'self' => 'http://example.com/articles',
                    ],
                    'data' => [
                        'type' => 'articles',
                        'id' => '1',
                        'attributes' => [
                            'title' => 'First'
                        ]
                    ],
                    'anything' => 'not valid'
                ],
                false,
                Messages::ONLY_ALLOWED_MEMBERS
            ],
            'no valid primary data' => [
                [
                    'links' => [
                        'self' => 'http://example.com/articles',
                    ],
                    'data' => [
                        'type' => 'articles',
                        'id' => 1,
                        'attributes' => [
                            'title' => 'First'
                        ]
                    ]
                ],
                false,
                Messages::RESOURCE_ID_MEMBER_IS_NOT_STRING
            ],
            'no valid errors object' => [
                [
                    'errors' => [
                        'wrong' => 'not valid'
                    ]
                ],
                false,
                Messages::ERRORS_OBJECT_NOT_ARRAY
            ],
            'no valid meta' => [
                [
                    'meta' => [
                        'key+' => 'not valid'
                    ],
                    'data' => [
                        'type' => 'articles',
                        'id' => '1',
                        'attributes' => [
                            'title' => 'First'
                        ]
                    ]
                ],
                false,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ],
            'no valid jsonapi' => [
                [
                    'jsonapi' => [
                        'version' => 1
                    ],
                    'data' => [
                        'type' => 'articles',
                        'id' => '1',
                        'attributes' => [
                            'title' => 'First'
                        ]
                    ]
                ],
                false,
                Messages::JSONAPI_VERSION_IS_NOT_STRING
            ],
            'no valid included' => [
                [
                    'included' => [
                        'key' => 'not valid'
                    ],
                    'data' => [
                        'type' => 'articles',
                        'id' => '1',
                        'attributes' => [
                            'title' => 'First'
                        ]
                    ]
                ],
                false,
                Messages::MUST_BE_ARRAY_OF_OBJECTS
            ],
            'bad value for top-level links member' => [
                [
                    'links' => [
                        'self' => 'http://example.com/articles',
                        'forbidden' => 'not valid'
                    ],
                    'data' => [
                        'type' => 'articles',
                        'id' => '1',
                        'attributes' => [
                            'title' => 'First'
                        ]
                    ]
                ],
                false,
                Messages::ONLY_ALLOWED_MEMBERS
            ]
        ];
    }

    /**
     * @test
     */
    public function topLevelLinksObjectIsValid()
    {
        $links = [
            'self' => 'url'
        ];
        $strict = false;

        JsonApiAssert::assertIsValidTopLevelLinksMember($links, $strict);
    }

    /**
     * @test
     * @dataProvider notValidTopLevelLinksObjectProvider
     */
    public function topLevelLinksObjectIsNotValid($json, $strict, $failureMessage)
    {
        $this->setFailure($failureMessage);
        JsonApiAssert::assertIsValidTopLevelLinksMember($json, $strict);
    }

    public function notValidTopLevelLinksObjectProvider()
    {
        return [
            'not allowed member' => [
                [
                    'anything' => 'not allowed'
                ],
                false,
                Messages::ONLY_ALLOWED_MEMBERS
            ]
        ];
    }
}
