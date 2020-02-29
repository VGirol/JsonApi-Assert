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
        $this->setAssertionFailure($failureMessage);
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
                Messages::MUST_NOT_BE_ARRAY_OF_OBJECTS
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
                Messages::JSONAPI_OBJECT_VERSION_MEMBER_MUST_BE_STRING
            ],
            'meta not valid' => [
                [
                    Members::JSONAPI_VERSION => 'jsonapi 1.0',
                    Members::META => [
                        'key+' => 'not valid'
                    ]
                ],
                false,
                Messages::MEMBER_NAME_MUST_NOT_HAVE_RESERVED_CHARACTERS
            ],
            'meta with not safe member' => [
                [
                    Members::JSONAPI_VERSION => 'jsonapi 1.0',
                    Members::META => [
                        'not safe' => 'not valid'
                    ]
                ],
                true,
                Messages::MEMBER_NAME_MUST_NOT_HAVE_RESERVED_CHARACTERS
            ]
        ];
    }
}
