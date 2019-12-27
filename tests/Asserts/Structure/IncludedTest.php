<?php
namespace VGirol\JsonApiAssert\Tests\Asserts\Structure;

use VGirol\JsonApiAssert\Assert as JsonApiAssert;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiAssert\Tests\TestCase;
use VGirol\JsonApiConstant\Members;

class IncludedTest extends TestCase
{
    /**
     * @test
     */
    public function compoundDocumentIsValid()
    {
        $json = [
            Members::DATA => [
                [
                    Members::TYPE => 'articles',
                    Members::ID => '1',
                    Members::ATTRIBUTES => [
                        'attr' => 'test'
                    ],
                    Members::RELATIONSHIPS => [
                        'anonymous' => [
                            Members::META => [
                                'key' => 'value'
                            ]
                        ],
                        'test' => [
                            Members::DATA => [
                                Members::TYPE => 'something',
                                Members::ID => '10'
                            ]
                        ]
                    ]
                ],
                [
                    Members::TYPE => 'articles',
                    Members::ID => '2',
                    Members::ATTRIBUTES => [
                        'attr' => 'another'
                    ]
                ]
            ],
            Members::INCLUDED => [
                [
                    Members::TYPE => 'something',
                    Members::ID => '10',
                    Members::ATTRIBUTES => [
                        'attr' => 'test'
                    ],
                    Members::RELATIONSHIPS => [
                        'anonymous' => [
                            Members::DATA => [
                                Members::TYPE => 'second',
                                Members::ID => '12'
                            ]
                        ]
                    ]
                ],
                [
                    Members::TYPE => 'second',
                    Members::ID => '12',
                    Members::ATTRIBUTES => [
                        'attr' => 'another test'
                    ]
                ]
            ]
        ];
        $strict = false;

        JsonApiAssert::assertIsValidIncludedCollection($json[Members::INCLUDED], $json[Members::DATA], $strict);
    }

    /**
     * @test
     * @dataProvider notValidIncludedProvider
     */
    public function compoundDocumentIsNotValid($json, $strict, $failureMessage)
    {
        $this->setFailure($failureMessage);
        JsonApiAssert::assertIsValidIncludedCollection($json[Members::INCLUDED], $json[Members::DATA], $strict);
    }

    public function notValidIncludedProvider()
    {
        return [
            'included member is not a resource collection' => [
                [
                    Members::DATA => [],
                    Members::INCLUDED => [
                        Members::ID => '1',
                        Members::TYPE => 'test'
                    ]
                ],
                false,
                Messages::MUST_BE_ARRAY_OF_OBJECTS
            ],
            'one included resource is not identified by a resource identifier object' => [
                [
                    Members::DATA => [
                        Members::TYPE => 'articles',
                        Members::ID => '1',
                        Members::RELATIONSHIPS => [
                            'anonymous' => [
                                Members::DATA => [
                                    Members::TYPE => 'something',
                                    Members::ID => '10'
                                ]
                            ]
                        ]
                    ],
                    Members::INCLUDED => [
                        [
                            Members::TYPE => 'something',
                            Members::ID => '10',
                            Members::ATTRIBUTES => [
                                'attr' => 'test'
                            ]
                        ],
                        [
                            Members::TYPE => 'something',
                            Members::ID => '12',
                            Members::ATTRIBUTES => [
                                'attr' => 'another'
                            ]
                        ]
                    ]
                ],
                false,
                Messages::INCLUDED_RESOURCE_NOT_LINKED
            ],
            'a resource is included twice' => [
                [
                    Members::DATA => [
                        Members::TYPE => 'articles',
                        Members::ID => '1',
                        Members::RELATIONSHIPS => [
                            'anonymous' => [
                                Members::DATA => [
                                    Members::TYPE => 'something',
                                    Members::ID => '10'
                                ]
                            ]
                        ]
                    ],
                    Members::INCLUDED => [
                        [
                            Members::TYPE => 'something',
                            Members::ID => '10',
                            Members::ATTRIBUTES => [
                                'attr' => 'test'
                            ]
                        ],
                        [
                            Members::TYPE => 'something',
                            Members::ID => '10',
                            Members::ATTRIBUTES => [
                                'attr' => 'test'
                            ]
                        ]
                    ]
                ],
                false,
                Messages::COMPOUND_DOCUMENT_ONLY_ONE_RESOURCE
            ],
            'an included resource is not valid' => [
                [
                    Members::DATA => [
                        Members::TYPE => 'articles',
                        Members::ID => '1',
                        Members::RELATIONSHIPS => [
                            'anonymous' => [
                                Members::DATA => [
                                    Members::TYPE => 'something',
                                    Members::ID => '10'
                                ]
                            ]
                        ]
                    ],
                    Members::INCLUDED => [
                        [
                            Members::TYPE => 'something',
                            Members::ID => '10'
                        ]
                    ]
                ],
                false,
                sprintf(
                    Messages::CONTAINS_AT_LEAST_ONE,
                    implode(', ', [Members::ATTRIBUTES, Members::RELATIONSHIPS, Members::LINKS, Members::META])
                )
            ]
        ];
    }
}
