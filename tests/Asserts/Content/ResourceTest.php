<?php

namespace VGirol\JsonApiAssert\Tests\Asserts\Content;

use VGirol\JsonApiAssert\Assert;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiAssert\Tests\TestCase;

class ResourceTest extends TestCase
{
    /**
     * @test
     */
    public function resourceObjectEquals()
    {
        $expected = [
            'id' => '123',
            'type' => 'test',
            'attributes' => [
                'attr1' => 'value1'
            ]
        ];
        $json = [
            'id' => '123',
            'type' => 'test',
            'attributes' => [
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
            'id' => '123',
            'type' => 'test',
            'attributes' => [
                'attr1' => 'value1'
            ]
        ];
        $json = [
            'id' => '456',
            'type' => 'test',
            'attributes' => [
                'attr1' => 'value1'
            ]
        ];

        $this->setFailureException();

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
                'id' => '456',
                'type' => 'test',
                'attributes' => [
                    'attr2' => 'value2'
                ]
            ],
            [
                'id' => '123',
                'type' => 'test',
                'attributes' => [
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
                    'id' => '123',
                    'type' => 'test',
                    'attributes' => [
                        'attr1' => 'value1'
                    ]
                ]
            ],
            'resources collection' => [
                [
                    [
                        'id' => '456',
                        'type' => 'test',
                        'attributes' => [
                            'attr2' => 'value2'
                        ]
                    ],
                    [
                        'id' => '123',
                        'type' => 'test',
                        'attributes' => [
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
            'id' => '123',
            'type' => 'test',
            'attributes' => [
                'attr1' => 'value1'
            ]
        ];
        $json = [
            [
                'id' => '456',
                'type' => 'test',
                'attributes' => [
                    'attr2' => 'value2'
                ]
            ],
            [
                'id' => '789',
                'type' => 'test',
                'attributes' => [
                    'attr3' => 'value3'
                ]
            ]
        ];

        $this->setFailureException();

        Assert::assertResourceCollectionContains($expected, $json);
    }

    /**
     * @test
     */
    public function resourceCollectionEquals()
    {
        $expected = [
            [
                'id' => '123',
                'type' => 'test',
                'attributes' => [
                    'attr1' => 'value1'
                ]
            ]
        ];
        $json = [
            [
                'id' => '123',
                'type' => 'test',
                'attributes' => [
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
                'id' => '456',
                'type' => 'test',
                'attributes' => [
                    'attr2' => 'value2'
                ]
            ],
            [
                'id' => '123',
                'type' => 'test',
                'attributes' => [
                    'attr1' => 'value1'
                ]
            ]
        ];

        $this->setFailureException($failureMsg);

        Assert::assertResourceCollectionEquals($expected, $json);
    }

    public function resourceCollectionEqualsFailedProvider()
    {
        return [
            'not an array of objects' => [
                [
                    'id' => '123',
                    'type' => 'test',
                    'attributes' => [
                        'attr1' => 'value1'
                    ]
                ],
                Messages::MUST_BE_ARRAY_OF_OBJECTS
            ],
            'not same count' => [
                [
                    [
                        'id' => '456',
                        'type' => 'test',
                        'attributes' => [
                            'attr2' => 'value2'
                        ]
                    ]
                ],
                sprintf(Messages::RESOURCE_COLLECTION_HAVE_NOT_SAME_LENGTH, 2, 1)
            ],
            'not equal' => [
                [
                    [
                        'id' => '456',
                        'type' => 'test',
                        'attributes' => [
                            'attr2' => 'value2'
                        ]
                    ],
                    [
                        'id' => '789',
                        'type' => 'test',
                        'attributes' => [
                            'attr1' => 'value1'
                        ]
                    ]
                ],
                null
            ]
        ];
    }
}
