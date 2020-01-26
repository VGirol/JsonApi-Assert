<?php

namespace VGirol\JsonApiAssert\Tests\Asserts\Structure;

use VGirol\JsonApiAssert\Assert as JsonApiAssert;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiAssert\Tests\TestCase;
use VGirol\JsonApiConstant\Members;

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
                    Members::TYPE => 'people',
                    Members::ID => '9'
                ],
                false
            ],
            'array of resource identifier objects' => [
                [
                    [
                        Members::TYPE => 'people',
                        Members::ID => '9'
                    ],
                    [
                        Members::TYPE => 'people',
                        Members::ID => '10'
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
                Messages::RESOURCE_LINKAGE_BAD_TYPE
            ],
            'not valid single resource identifier object' => [
                [
                    Members::TYPE => 'people',
                    Members::ID => '9',
                    'anything' => 'not valid'
                ],
                false,
                Messages::ONLY_ALLOWED_MEMBERS
            ],
            'not valid array of resource identifier objects' => [
                [
                    [
                        Members::TYPE => 'people',
                        Members::ID => '9',
                        'anything' => 'not valid'
                    ],
                    [
                        Members::TYPE => 'people',
                        Members::ID => '10'
                    ]
                ],
                false,
                Messages::ONLY_ALLOWED_MEMBERS
            ],
            'not safe member name' => [
                [
                    Members::TYPE => 'people',
                    Members::ID => '9',
                    Members::META => [
                        'not valid' => 'due to the blank character'
                    ]
                ],
                true,
                Messages::MEMBER_NAME_MUST_NOT_HAVE_RESERVED_CHARACTERS
            ]
        ];
    }

    /**
     * @test
     */
    public function resourceIdentifierObjectIsValid()
    {
        $data = [
            Members::ID => '1',
            Members::TYPE => 'test',
            Members::META => [
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
                Messages::RESOURCE_IDENTIFIER_MUST_BE_ARRAY
            ],
            'id is missing' => [
                [
                    Members::TYPE => 'test'
                ],
                false,
                Messages::RESOURCE_ID_MEMBER_IS_ABSENT
            ],
            'id is not valid' => [
                [
                    Members::ID => 1,
                    Members::TYPE => 'test'
                ],
                false,
                Messages::RESOURCE_ID_MEMBER_MUST_BE_STRING
            ],
            'type is missing' => [
                [
                    Members::ID => '1'
                ],
                false,
                Messages::RESOURCE_TYPE_MEMBER_IS_ABSENT
            ],
            'type is not valid' => [
                [
                    Members::ID => '1',
                    Members::TYPE => 404
                ],
                false,
                Messages::RESOURCE_TYPE_MEMBER_MUST_BE_STRING
            ],
            'member not allowed' => [
                [
                    Members::ID => '1',
                    Members::TYPE => 'test',
                    'wrong' => 'wrong'
                ],
                false,
                Messages::ONLY_ALLOWED_MEMBERS
            ],
            'meta has not valid member name' => [
                [
                    Members::ID => '1',
                    Members::TYPE => 'test',
                    Members::META => [
                        'not valid' => 'due to the blank character'
                    ]
                ],
                true,
                Messages::MEMBER_NAME_MUST_NOT_HAVE_RESERVED_CHARACTERS
            ]
        ];
    }
}
