<?php

namespace VGirol\JsonApiAssert\Tests\Asserts\Content;

use VGirol\JsonApiAssert\Assert;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiAssert\Tests\TestCase;

class ResourceLTest extends TestCase
{
    /**
     * @test
     */
    public function resourceIdentifierEquals()
    {
        $expected = [
            'id' => '123',
            'type' => 'test'
        ];
        $json = [
            'id' => '123',
            'type' => 'test'
        ];

        Assert::assertResourceIdentifierEquals($expected, $json);
    }

    /**
     * @test
     */
    public function resourceIdentifierEqualsFailed()
    {
        $expected = [
            'id' => '123',
            'type' => 'test'
        ];
        $json = [
            'id' => '456',
            'type' => 'test'
        ];

        $this->setFailure();

        Assert::assertResourceIdentifierEquals($expected, $json);
    }

    /**
     * @test
     */
    public function resourceIdentifierCollectionEquals()
    {
        $expected = [
            [
                'id' => '123',
                'type' => 'test'
            ],
            [
                'id' => '456',
                'type' => 'test'
            ]
        ];
        $json = [
            [
                'id' => '123',
                'type' => 'test'
            ],
            [
                'id' => '456',
                'type' => 'test'
            ]
        ];

        Assert::assertResourceIdentifierCollectionEquals($expected, $json);
    }

    /**
     * @test
     * @dataProvider resourceIdentifierCollectionEqualsFailedProvider
     */
    public function resourceIdentifierCollectionEqualsFailed($expected, $json, $failureMsg)
    {
        $this->setFailure($failureMsg);

        Assert::assertResourceIdentifierCollectionEquals($expected, $json);
    }

    public function resourceIdentifierCollectionEqualsFailedProvider()
    {
        return [
            'not an array of objects' => [
                [
                    [
                        'id' => '123',
                        'type' => 'test'
                    ],
                    [
                        'id' => '456',
                        'type' => 'test'
                    ]
                ],
                [
                    'id' => '123',
                    'type' => 'test'
                ],
                Messages::MUST_BE_ARRAY_OF_OBJECTS
            ],
            'not same count' => [
                [
                    [
                        'id' => '123',
                        'type' => 'test'
                    ],
                    [
                        'id' => '456',
                        'type' => 'test'
                    ]
                ],
                [
                    [
                        'id' => '123',
                        'type' => 'test'
                    ]
                ],
                $this->formatAsRegex(Messages::RESOURCE_LINKAGE_COLLECTION_HAVE_NOT_SAME_LENGTH)
            ],
            'not same value' => [
                [
                    [
                        'id' => '123',
                        'type' => 'test'
                    ],
                    [
                        'id' => '456',
                        'type' => 'test'
                    ]
                ],
                [
                    [
                        'id' => '123',
                        'type' => 'test'
                    ],
                    [
                        'id' => '789',
                        'type' => 'test'
                    ]
                ],
                $this->formatAsRegex(Messages::RESOURCE_IDENTIFIER_IS_NOT_EQUAL)
            ]
        ];
    }

    /**
     * @test
     * @dataProvider resourceLinkageEqualsProvider
     */
    public function resourceLinkageEquals($expected, $json, $strict)
    {
        Assert::assertResourceLinkageEquals($expected, $json, $strict);
    }

    public function resourceLinkageEqualsProvider()
    {
        return [
            'resource linkage is null' => [
                null,
                null,
                true
            ],
            'single resource identifier' => [
                [
                    'id' => '123',
                    'type' => 'test'
                ],
                [
                    'id' => '123',
                    'type' => 'test'
                ],
                true
            ],
            'empty collection' => [
                [],
                [],
                true
            ],
            'collection with only one resource identifiers' => [
                [
                    [
                        'id' => '123',
                        'type' => 'test'
                    ]
                ],
                [
                    [
                        'id' => '123',
                        'type' => 'test'
                    ]
                ],
                true
            ],
            'collection of resource identifiers' => [
                [
                    [
                        'id' => '123',
                        'type' => 'test'
                    ],
                    [
                        'id' => '456',
                        'type' => 'test'
                    ]
                ],
                [
                    [
                        'id' => '123',
                        'type' => 'test'
                    ],
                    [
                        'id' => '456',
                        'type' => 'test'
                    ]
                ],
                true
            ]
        ];
    }

    /**
     * @test
     * @dataProvider resourceLinkageEqualsFailedProvider
     */
    public function resourceLinkageEqualsFailed($expected, $json, $strict, $failureMsg)
    {
        $this->setFailure($failureMsg);

        Assert::assertResourceLinkageEquals($expected, $json, $strict);
    }

    public function resourceLinkageEqualsFailedProvider()
    {
        return [
            'is not valid resource linkage' => [
                null,
                'notValid',
                true,
                Messages::RESOURCE_LINKAGE_NOT_ARRAY
            ],
            'is not null as expected' => [
                null,
                [
                    'id' => '123',
                    'type' => 'test'
                ],
                true,
                $this->formatAsRegex(Messages::RESOURCE_LINKAGE_MUST_BE_NULL)
            ],
            'is null but resource identifier expected' => [
                [
                    'id' => '123',
                    'type' => 'test'
                ],
                null,
                true,
                Messages::RESOURCE_LINKAGE_MUST_NOT_BE_NULL
            ],
            'is not a single resource identifier' => [
                [
                    'id' => '123',
                    'type' => 'test'
                ],
                [
                    [
                        'id' => '123',
                        'type' => 'test'
                    ],
                    [
                        'id' => '456',
                        'type' => 'test'
                    ]
                ],
                true,
                Messages::MUST_NOT_BE_ARRAY_OF_OBJECTS
            ],
            'is not the same resource identifier' => [
                [
                    'id' => '123',
                    'type' => 'test'
                ],
                [
                    'id' => '456',
                    'type' => 'test'
                ],
                true,
                $this->formatAsRegex(Messages::RESOURCE_IDENTIFIER_IS_NOT_EQUAL)
            ],
            'is not empty collection as expected' => [
                [],
                [
                    'id' => '123',
                    'type' => 'test'
                ],
                true,
                Messages::RESOURCE_LINKAGE_COLLECTION_MUST_BE_EMPTY
            ],
            'is not same collection' => [
                [
                    [
                        'id' => '123',
                        'type' => 'test'
                    ],
                    [
                        'id' => '456',
                        'type' => 'test'
                    ]
                ],
                [
                    [
                        'id' => '123',
                        'type' => 'test'
                    ],
                    [
                        'id' => '789',
                        'type' => 'test'
                    ]
                ],
                true,
                $this->formatAsRegex(Messages::RESOURCE_IDENTIFIER_IS_NOT_EQUAL)
            ]
        ];
    }
}
