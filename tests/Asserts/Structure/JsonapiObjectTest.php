<?php

namespace VGirol\JsonApiAssert\Tests\Asserts\Structure;

use VGirol\JsonApiAssert\Assert as JsonApiAssert;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiAssert\Tests\TestCase;
use VGirol\JsonApiConstant\Members;

class JsonapiObjectTest extends TestCase
{
    /**
     * @test
     */
    public function jsonapiObjectIsValid()
    {
        $data = [
            Members::JSONAPI_VERSION => 'jsonapi v1.1',
            Members::META => [
                'allowed' => 'valid'
            ]
        ];
        $strict = false;

        JsonApiAssert::assertIsValidJsonapiObject($data, $strict);
    }

    /**
     * @test
     * @dataProvider notValidJsonapiObjectProvider
     */
    public function jsonapiObjectIsNotValid($data, $strict, $failureMessage)
    {
        $this->setFailure($failureMessage);
        JsonApiAssert::assertIsValidJsonapiObject($data, $strict);
    }

    public function notValidJsonapiObjectProvider()
    {
        return [
            'array of objects' => [
                [
                    [
                        Members::JSONAPI_VERSION => 'jsonapi 1.0'
                    ],
                    [
                        'not' => 'allowed'
                    ]
                ],
                false,
                Messages::OBJECT_NOT_ARRAY
            ],
            'not allowed member' => [
                [
                    Members::JSONAPI_VERSION => 'jsonapi 1.0',
                    'not' => 'allowed'
                ],
                false,
                Messages::ONLY_ALLOWED_MEMBERS
            ],
            'version is not a string' => [
                [
                    Members::JSONAPI_VERSION => 123
                ],
                false,
                Messages::JSONAPI_VERSION_IS_NOT_STRING
            ],
            'meta not valid' => [
                [
                    Members::JSONAPI_VERSION => 'jsonapi 1.0',
                    Members::META => [
                        'key+' => 'not valid'
                    ]
                ],
                false,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ],
            'meta with not safe member' => [
                [
                    Members::JSONAPI_VERSION => 'jsonapi 1.0',
                    Members::META => [
                        'not safe' => 'not valid'
                    ]
                ],
                true,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ]
        ];
    }
}
