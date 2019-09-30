<?php
namespace VGirol\JsonApiAssert\Tests\Asserts\Structure;

use VGirol\JsonApiAssert\Assert as JsonApiAssert;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiAssert\Tests\TestCase;

class PrimaryDataTest extends TestCase
{
    /**
     * @test
     * @dataProvider validPrimaryDataProvider
     */
    public function primaryDataIsValid($data, $strict)
    {
        JsonApiAssert::assertIsValidPrimaryData($data, $strict);
    }

    public function validPrimaryDataProvider()
    {
        return [
            'null' => [
                null,
                false
            ],
            'empty collection' => [
                [],
                false
            ],
            'resource identifier collection' => [
                [
                    [
                        'type' => 'test',
                        'id' => '2'
                    ],
                    [
                        'type' => 'test',
                        'id' => '3'
                    ]
                ],
                false
            ],
            'resource object collection' => [
                [
                    [
                        'type' => 'test',
                        'id' => '2',
                        'attributes' => [
                            'title' => 'test'
                        ]
                    ],
                    [
                        'type' => 'test',
                        'id' => '3',
                        'attributes' => [
                            'title' => 'another'
                        ]
                    ]
                ],
                false
            ],
            'unique resource identifier' => [
                [
                    'type' => 'test',
                    'id' => '2'
                ],
                false
            ],
            'unique resource object' => [
                [
                    'type' => 'test',
                    'id' => '2',
                    'attributes' => [
                        'anything' => 'ok'
                    ]
                ],
                false
            ]
        ];
    }

    /**
     * @test
     * @dataProvider notValidPrimaryDataProvider
     */
    public function primaryDataIsNotValid($data, $strict, $failureMessage)
    {
        $this->setFailure($failureMessage);
        JsonApiAssert::assertIsValidPrimaryData($data, $strict);
    }

    public function notValidPrimaryDataProvider()
    {
        return [
            'not an array' => [
                'bad',
                false,
                Messages::PRIMARY_DATA_NOT_ARRAY
            ],
            'collection with different type of resource objects' => [
                [
                    [
                        'type' => 'test',
                        'id' => '1'
                    ],
                    [
                        'type' => 'test',
                        'id' => '2',
                        'attributes' => [
                            'anything' => 'valid'
                        ]
                    ]
                ],
                false,
                Messages::PRIMARY_DATA_SAME_TYPE
            ],
            'collection with not valid resource identifier objects' => [
                [
                    [
                        'type' => 'test',
                        'id' => '1'
                    ],
                    [
                        'type' => 'test',
                        'id' => '2',
                        'unvalid' => 'wrong'
                    ]
                ],
                false,
                Messages::ONLY_ALLOWED_MEMBERS
            ],
            'not safe meta member' => [
                [
                    'type' => 'test',
                    'id' => '2',
                    'attributes' => [
                        'anything' => 'valid'
                    ],
                    'meta' => [
                        'not valid' => 'due to the blank character'
                    ]
                ],
                true,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ]
        ];
    }
}
