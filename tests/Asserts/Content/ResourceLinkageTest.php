<?php

namespace VGirol\JsonApiAssert\Tests\Asserts\Content;

use VGirol\JsonApiAssert\Assert;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiAssert\Tests\TestCase;
use VGirol\JsonApiConstant\Members;

class ResourceLinkageTest extends TestCase
{
    /**
     * @test
     */
    public function resourceIdentifierEquals()
    {
        $expected = [
            Members::ID => '123',
            Members::TYPE => 'test'
        ];
        $json = [
            Members::ID => '123',
            Members::TYPE => 'test'
        ];

        Assert::assertResourceIdentifierEquals($expected, $json);
    }

    /**
     * @test
     */
    public function resourceIdentifierEqualsFailed()
    {
        $expected = [
            Members::ID => '123',
            Members::TYPE => 'test'
        ];
        $json = [
            Members::ID => '456',
            Members::TYPE => 'test'
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
                Members::ID => '123',
                Members::TYPE => 'test'
            ],
            [
                Members::ID => '456',
                Members::TYPE => 'test'
            ]
        ];
        $json = [
            [
                Members::ID => '123',
                Members::TYPE => 'test'
            ],
            [
                Members::ID => '456',
                Members::TYPE => 'test'
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
                        Members::ID => '123',
                        Members::TYPE => 'test'
                    ],
                    [
                        Members::ID => '456',
                        Members::TYPE => 'test'
                    ]
                ],
                [
                    Members::ID => '123',
                    Members::TYPE => 'test'
                ],
                Messages::MUST_BE_ARRAY_OF_OBJECTS
            ],
            'not same count' => [
                [
                    [
                        Members::ID => '123',
                        Members::TYPE => 'test'
                    ],
                    [
                        Members::ID => '456',
                        Members::TYPE => 'test'
                    ]
                ],
                [
                    [
                        Members::ID => '123',
                        Members::TYPE => 'test'
                    ]
                ],
                $this->formatAsRegex(Messages::RESOURCE_LINKAGE_COLLECTION_HAVE_NOT_SAME_LENGTH)
            ],
            'not same value' => [
                [
                    [
                        Members::ID => '123',
                        Members::TYPE => 'test'
                    ],
                    [
                        Members::ID => '456',
                        Members::TYPE => 'test'
                    ]
                ],
                [
                    [
                        Members::ID => '123',
                        Members::TYPE => 'test'
                    ],
                    [
                        Members::ID => '789',
                        Members::TYPE => 'test'
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
                    Members::ID => '123',
                    Members::TYPE => 'test'
                ],
                [
                    Members::ID => '123',
                    Members::TYPE => 'test'
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
                        Members::ID => '123',
                        Members::TYPE => 'test'
                    ]
                ],
                [
                    [
                        Members::ID => '123',
                        Members::TYPE => 'test'
                    ]
                ],
                true
            ],
            'collection of resource identifiers' => [
                [
                    [
                        Members::ID => '123',
                        Members::TYPE => 'test'
                    ],
                    [
                        Members::ID => '456',
                        Members::TYPE => 'test'
                    ]
                ],
                [
                    [
                        Members::ID => '123',
                        Members::TYPE => 'test'
                    ],
                    [
                        Members::ID => '456',
                        Members::TYPE => 'test'
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
                Messages::RESOURCE_LINKAGE_BAD_TYPE
            ],
            'is not null as expected' => [
                null,
                [
                    Members::ID => '123',
                    Members::TYPE => 'test'
                ],
                true,
                $this->formatAsRegex(Messages::RESOURCE_LINKAGE_MUST_BE_NULL)
            ],
            'is null but resource identifier expected' => [
                [
                    Members::ID => '123',
                    Members::TYPE => 'test'
                ],
                null,
                true,
                Messages::RESOURCE_LINKAGE_MUST_NOT_BE_NULL
            ],
            'is not a single resource identifier' => [
                [
                    Members::ID => '123',
                    Members::TYPE => 'test'
                ],
                [
                    [
                        Members::ID => '123',
                        Members::TYPE => 'test'
                    ],
                    [
                        Members::ID => '456',
                        Members::TYPE => 'test'
                    ]
                ],
                true,
                Messages::MUST_NOT_BE_ARRAY_OF_OBJECTS
            ],
            'is not the same resource identifier' => [
                [
                    Members::ID => '123',
                    Members::TYPE => 'test'
                ],
                [
                    Members::ID => '456',
                    Members::TYPE => 'test'
                ],
                true,
                $this->formatAsRegex(Messages::RESOURCE_IDENTIFIER_IS_NOT_EQUAL)
            ],
            'is not empty collection as expected' => [
                [],
                [
                    Members::ID => '123',
                    Members::TYPE => 'test'
                ],
                true,
                Messages::RESOURCE_LINKAGE_COLLECTION_MUST_BE_EMPTY
            ],
            'is not same collection' => [
                [
                    [
                        Members::ID => '123',
                        Members::TYPE => 'test'
                    ],
                    [
                        Members::ID => '456',
                        Members::TYPE => 'test'
                    ]
                ],
                [
                    [
                        Members::ID => '123',
                        Members::TYPE => 'test'
                    ],
                    [
                        Members::ID => '789',
                        Members::TYPE => 'test'
                    ]
                ],
                true,
                $this->formatAsRegex(Messages::RESOURCE_IDENTIFIER_IS_NOT_EQUAL)
            ]
        ];
    }
}
