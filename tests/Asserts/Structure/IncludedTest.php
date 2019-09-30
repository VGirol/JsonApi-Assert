<?php
namespace VGirol\JsonApiAssert\Tests\Asserts\Structure;

use VGirol\JsonApiAssert\Assert as JsonApiAssert;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiAssert\Tests\TestCase;

class IncludedTest extends TestCase
{
    /**
     * @test
     */
    public function compoundDocumentIsValid()
    {
        $json = [
            'data' => [
                [
                    'type' => 'articles',
                    'id' => '1',
                    'attributes' => [
                        'title' => 'test'
                    ],
                    'relationships' => [
                        'anonymous' => [
                            'meta' => [
                                'key' => 'value'
                            ]
                        ],
                        'test' => [
                            'data' => [
                                'type' => 'first',
                                'id' => '10'
                            ]
                        ]
                    ]
                ],
                [
                    'type' => 'articles',
                    'id' => '2',
                    'attributes' => [
                        'title' => 'another'
                    ]
                ]
            ],
            'included' => [
                [
                    'type' => 'first',
                    'id' => '10',
                    'attributes' => [
                        'title' => 'test'
                    ],
                    'relationships' => [
                        'test' => [
                            'data' => [
                                'type' => 'second',
                                'id' => '12'
                            ]
                        ]
                    ]
                ],
                [
                    'type' => 'second',
                    'id' => '12',
                    'attributes' => [
                        'title' => 'another test'
                    ]
                ]
            ]
        ];
        $strict = false;

        JsonApiAssert::assertIsValidIncludedCollection($json['included'], $json['data'], $strict);
    }

    /**
     * @test
     * @dataProvider notValidIncludedProvider
     */
    public function compoundDocumentIsNotValid($json, $strict, $failureMessage)
    {
        $this->setFailure($failureMessage);
        JsonApiAssert::assertIsValidIncludedCollection($json['included'], $json['data'], $strict);
    }

    public function notValidIncludedProvider()
    {
        return [
            'included member is not a resource collection' => [
                [
                    'data' => [],
                    'included' => [
                        'id' => '1',
                        'type' => 'test'
                    ]
                ],
                false,
                Messages::MUST_BE_ARRAY_OF_OBJECTS
            ],
            'one included resource is not identified by a resource identifier object' => [
                [
                    'data' => [
                        'type' => 'articles',
                        'id' => '1',
                        'relationships' => [
                            'test' => [
                                'data' => [
                                    'type' => 'first',
                                    'id' => '10'
                                ]
                            ]
                        ]
                    ],
                    'included' => [
                        [
                            'type' => 'first',
                            'id' => '10',
                            'attributes' => [
                                'title' => 'test'
                            ]
                        ],
                        [
                            'type' => 'first',
                            'id' => '12',
                            'attributes' => [
                                'title' => 'another'
                            ]
                        ]
                    ]
                ],
                false,
                Messages::INCLUDED_RESOURCE_NOT_LINKED
            ],
            'a resource is included twice' => [
                [
                    'data' => [
                        'type' => 'articles',
                        'id' => '1',
                        'relationships' => [
                            'test' => [
                                'data' => [
                                    'type' => 'first',
                                    'id' => '10'
                                ]
                            ]
                        ]
                    ],
                    'included' => [
                        [
                            'type' => 'first',
                            'id' => '10',
                            'attributes' => [
                                'title' => 'test'
                            ]
                        ],
                        [
                            'type' => 'first',
                            'id' => '10',
                            'attributes' => [
                                'title' => 'test'
                            ]
                        ]
                    ]
                ],
                false,
                Messages::COMPOUND_DOCUMENT_ONLY_ONE_RESOURCE
            ],
            'an included resource is not valid' => [
                [
                    'data' => [
                        'type' => 'articles',
                        'id' => '1',
                        'relationships' => [
                            'test' => [
                                'data' => [
                                    'type' => 'first',
                                    'id' => '10'
                                ]
                            ]
                        ]
                    ],
                    'included' => [
                        [
                            'type' => 'first',
                            'id' => '10'
                        ]
                    ]
                ],
                false,
                sprintf(
                    Messages::CONTAINS_AT_LEAST_ONE,
                    implode(', ', ['attributes', 'relationships', 'links', 'meta'])
                )
            ]
        ];
    }
}
