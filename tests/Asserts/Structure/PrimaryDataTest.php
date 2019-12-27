<?php
namespace VGirol\JsonApiAssert\Tests\Asserts\Structure;

use VGirol\JsonApiAssert\Assert as JsonApiAssert;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiAssert\Tests\TestCase;
use VGirol\JsonApiConstant\Members;

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
                        Members::TYPE => 'test',
                        Members::ID => '2'
                    ],
                    [
                        Members::TYPE => 'test',
                        Members::ID => '3'
                    ]
                ],
                false
            ],
            'resource object collection' => [
                [
                    [
                        Members::TYPE => 'test',
                        Members::ID => '2',
                        Members::ATTRIBUTES => [
                            'attr' => 'test'
                        ]
                    ],
                    [
                        Members::TYPE => 'test',
                        Members::ID => '3',
                        Members::ATTRIBUTES => [
                            'attr' => 'another'
                        ]
                    ]
                ],
                false
            ],
            'unique resource identifier' => [
                [
                    Members::TYPE => 'test',
                    Members::ID => '2'
                ],
                false
            ],
            'unique resource object' => [
                [
                    Members::TYPE => 'test',
                    Members::ID => '2',
                    Members::ATTRIBUTES => [
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
                        Members::TYPE => 'test',
                        Members::ID => '1'
                    ],
                    [
                        Members::TYPE => 'test',
                        Members::ID => '2',
                        Members::ATTRIBUTES => [
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
                        Members::TYPE => 'test',
                        Members::ID => '1'
                    ],
                    [
                        Members::TYPE => 'test',
                        Members::ID => '2',
                        'unvalid' => 'wrong'
                    ]
                ],
                false,
                Messages::ONLY_ALLOWED_MEMBERS
            ],
            'not safe meta member' => [
                [
                    Members::TYPE => 'test',
                    Members::ID => '2',
                    Members::ATTRIBUTES => [
                        'anything' => 'valid'
                    ],
                    Members::META => [
                        'not valid' => 'due to the blank character'
                    ]
                ],
                true,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ]
        ];
    }
}
