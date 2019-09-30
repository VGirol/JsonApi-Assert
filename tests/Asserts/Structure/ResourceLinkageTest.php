<?php
namespace VGirol\JsonApiAssert\Tests\Asserts\Structure;

use VGirol\JsonApiAssert\Assert as JsonApiAssert;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiAssert\Tests\TestCase;

class ResourceLinkageTest extends TestCase
{
    /**
     * @test
     * @dataProvider validResourceLinkageProvider
     */
    public function resourceLinkageIsValid($data, $strict)
    {
        JsonApiAssert::assertIsValidResourceLinkage($data, $strict);
    }

    public function validResourceLinkageProvider()
    {
        return [
            'null' => [
                null,
                false
            ],
            'empty array' => [
                [],
                false
            ],
            'single resource identifier object' => [
                [
                    'type' => 'people',
                    'id' => '9'
                ],
                false
            ],
            'array of resource identifier objects' => [
                [
                    [
                        'type' => 'people',
                        'id' => '9'
                    ],
                    [
                        'type' => 'people',
                        'id' => '10'
                    ]
                ],
                false
            ]
        ];
    }

    /**
     * @test
     * @dataProvider notValidResourceLinkageProvider
     */
    public function resourceLinkageIsNotValid($data, $strict, $failureMessage)
    {
        $this->setFailure($failureMessage);
        JsonApiAssert::assertIsValidResourceLinkage($data, $strict);
    }

    public function notValidResourceLinkageProvider()
    {
        return [
            'not an array' => [
                'not valid',
                false,
                Messages::RESOURCE_LINKAGE_NOT_ARRAY
            ],
            'not valid single resource identifier object' => [
                [
                    'type' => 'people',
                    'id' => '9',
                    'anything' => 'not valid'
                ],
                false,
                Messages::ONLY_ALLOWED_MEMBERS
            ],
            'not valid array of resource identifier objects' => [
                [
                    [
                        'type' => 'people',
                        'id' => '9',
                        'anything' => 'not valid'
                    ],
                    [
                        'type' => 'people',
                        'id' => '10'
                    ]
                ],
                false,
                Messages::ONLY_ALLOWED_MEMBERS
            ],
            'not safe member name' => [
                [
                    'type' => 'people',
                    'id' => '9',
                    'meta' => [
                        'not valid' => 'due to the blank character'
                    ]
                ],
                true,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ]
        ];
    }

    /**
     * @test
     */
    public function resourceIdentifierObjectIsValid()
    {
        $data = [
            'id' => '1',
            'type' => 'test',
            'meta' => [
                'member' => 'is valid'
            ]
        ];
        $strict = false;

        JsonApiAssert::assertIsValidResourceIdentifierObject($data, $strict);
    }

    /**
     * @test
     * @dataProvider isNotValidResourceIdentifierObjectProvider
     */
    public function resourceIdentifierObjectIsNotValid($json, $strict, $failureMessage)
    {
        $this->setFailure($failureMessage);
        JsonApiAssert::assertIsValidResourceIdentifierObject($json, $strict);
    }

    public function isNotValidResourceIdentifierObjectProvider()
    {
        return [
            'not an array' => [
                'failed',
                false,
                Messages::RESOURCE_IDENTIFIER_IS_NOT_ARRAY
            ],
            'id is missing' => [
                [
                    'type' => 'test'
                ],
                false,
                Messages::RESOURCE_ID_MEMBER_IS_ABSENT
            ],
            'id is not valid' => [
                [
                    'id' => 1,
                    'type' => 'test'
                ],
                false,
                Messages::RESOURCE_ID_MEMBER_IS_NOT_STRING
            ],
            'type is missing' => [
                [
                    'id' => '1'
                ],
                false,
                Messages::RESOURCE_TYPE_MEMBER_IS_ABSENT
            ],
            'type is not valid' => [
                [
                    'id' => '1',
                    'type' => 404
                ],
                false,
                Messages::RESOURCE_TYPE_MEMBER_IS_NOT_STRING
            ],
            'member not allowed' => [
                [
                    'id' => '1',
                    'type' => 'test',
                    'wrong' => 'wrong'
                ],
                false,
                Messages::ONLY_ALLOWED_MEMBERS
            ],
            'meta has not valid member name' => [
                [
                    'id' => '1',
                    'type' => 'test',
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
