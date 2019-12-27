<?php

namespace VGirol\JsonApiAssert\Tests\Asserts\Content;

use VGirol\JsonApiAssert\Assert;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiAssert\Tests\TestCase;
use VGirol\JsonApiConstant\Members;

class ResourceTest extends TestCase
{
    /**
     * @test
     */
    public function resourceObjectEquals()
    {
        $expected = [
            Members::ID => '123',
            Members::TYPE => 'test',
            Members::ATTRIBUTES => [
                'attr1' => 'value1'
            ]
        ];
        $json = [
            Members::ID => '123',
            Members::TYPE => 'test',
            Members::ATTRIBUTES => [
                'attr1' => 'value1'
            ]
        ];

        Assert::assertResourceObjectEquals($expected, $json);
    }

    /**
     * @test
     */
    public function resourceObjectEqualsFailed()
    {
        $expected = [
            Members::ID => '123',
            Members::TYPE => 'test',
            Members::ATTRIBUTES => [
                'attr1' => 'value1'
            ]
        ];
        $json = [
            Members::ID => '456',
            Members::TYPE => 'test',
            Members::ATTRIBUTES => [
                'attr1' => 'value1'
            ]
        ];

        $this->setFailure(
            sprintf(Messages::RESOURCE_IS_NOT_EQUAL, var_export($json, true), var_export($expected, true))
        );

        Assert::assertResourceObjectEquals($expected, $json);
    }

    /**
     * @test
     * @dataProvider resourceCollectionContainsProvider
     */
    public function resourceCollectionContains($expected)
    {
        $json = [
            [
                Members::ID => '456',
                Members::TYPE => 'test',
                Members::ATTRIBUTES => [
                    'attr2' => 'value2'
                ]
            ],
            [
                Members::ID => '123',
                Members::TYPE => 'test',
                Members::ATTRIBUTES => [
                    'attr1' => 'value1'
                ]
            ]
        ];

        Assert::assertResourceCollectionContains($expected, $json);
    }

    public function resourceCollectionContainsProvider()
    {
        return [
            'single resource' => [
                [
                    Members::ID => '123',
                    Members::TYPE => 'test',
                    Members::ATTRIBUTES => [
                        'attr1' => 'value1'
                    ]
                ]
            ],
            'resources collection' => [
                [
                    [
                        Members::ID => '456',
                        Members::TYPE => 'test',
                        Members::ATTRIBUTES => [
                            'attr2' => 'value2'
                        ]
                    ],
                    [
                        Members::ID => '123',
                        Members::TYPE => 'test',
                        Members::ATTRIBUTES => [
                            'attr1' => 'value1'
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     * @test
     */
    public function resourceCollectionContainsFailed()
    {
        $expected = [
            Members::ID => '123',
            Members::TYPE => 'test',
            Members::ATTRIBUTES => [
                'attr1' => 'value1'
            ]
        ];
        $json = [
            [
                Members::ID => '456',
                Members::TYPE => 'test',
                Members::ATTRIBUTES => [
                    'attr2' => 'value2'
                ]
            ],
            [
                Members::ID => '789',
                Members::TYPE => 'test',
                Members::ATTRIBUTES => [
                    'attr3' => 'value3'
                ]
            ]
        ];

        $this->setFailure();

        Assert::assertResourceCollectionContains($expected, $json);
    }

    /**
     * @test
     */
    public function resourceCollectionEquals()
    {
        $expected = [
            [
                Members::ID => '123',
                Members::TYPE => 'test',
                Members::ATTRIBUTES => [
                    'attr1' => 'value1'
                ]
            ]
        ];
        $json = [
            [
                Members::ID => '123',
                Members::TYPE => 'test',
                Members::ATTRIBUTES => [
                    'attr1' => 'value1'
                ]
            ]
        ];

        Assert::assertResourceCollectionEquals($expected, $json);
    }

    /**
     * @test
     * @dataProvider resourceCollectionEqualsFailedProvider
     */
    public function resourceCollectionEqualsFailed($expected, $failureMsg)
    {
        $json = [
            [
                Members::ID => '456',
                Members::TYPE => 'test',
                Members::ATTRIBUTES => [
                    'attr2' => 'value2'
                ]
            ],
            [
                Members::ID => '123',
                Members::TYPE => 'test',
                Members::ATTRIBUTES => [
                    'attr1' => 'value1'
                ]
            ]
        ];

        $this->setFailure($failureMsg);

        Assert::assertResourceCollectionEquals($expected, $json);
    }

    public function resourceCollectionEqualsFailedProvider()
    {
        return [
            'not an array of objects' => [
                [
                    Members::ID => '123',
                    Members::TYPE => 'test',
                    Members::ATTRIBUTES => [
                        'attr1' => 'value1'
                    ]
                ],
                Messages::MUST_BE_ARRAY_OF_OBJECTS
            ],
            'not same count' => [
                [
                    [
                        Members::ID => '456',
                        Members::TYPE => 'test',
                        Members::ATTRIBUTES => [
                            'attr2' => 'value2'
                        ]
                    ]
                ],
                sprintf(Messages::RESOURCE_COLLECTION_HAVE_NOT_SAME_LENGTH, 2, 1)
            ],
            'not equal' => [
                [
                    [
                        Members::ID => '456',
                        Members::TYPE => 'test',
                        Members::ATTRIBUTES => [
                            'attr2' => 'value2'
                        ]
                    ],
                    [
                        Members::ID => '789',
                        Members::TYPE => 'test',
                        Members::ATTRIBUTES => [
                            'attr1' => 'value1'
                        ]
                    ]
                ],
                $this->formatAsRegex(Messages::RESOURCE_IS_NOT_EQUAL)
            ]
        ];
    }
}
