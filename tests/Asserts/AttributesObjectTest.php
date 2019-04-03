<?php
namespace VGirol\JsonApiAssert\Tests\Asserts;

use VGirol\JsonApiAssert\Assert as JsonApiAssert;
use VGirol\JsonApiAssert\Tests\TestCase;
use VGirol\JsonApiAssert\Messages;
use PHPUnit\Framework\Exception;

class AttributesObjectTest extends TestCase
{
    /**
     * @test
     */
    public function member_name_is_not_forbidden()
    {
        $name = 'valid';
        JsonApiAssert::assertIsNotForbiddenMemberName($name);
    }

    /**
     * @test
     * @dataProvider forbiddenMemberNameProvider
     */
    public function member_name_is_forbidden($data, $failureMessage)
    {
        $fn = function ($data) {
            JsonApiAssert::assertIsNotForbiddenMemberName($data);
        };

        JsonApiAssert::assertTestFail($fn, $failureMessage, $data);
    }

    public function forbiddenMemberNameProvider()
    {
        return [
            'relationships' => [
                'relationships',
                Messages::MEMBER_NAME_NOT_ALLOWED
            ],
            'links' => [
                'links',
                Messages::MEMBER_NAME_NOT_ALLOWED
            ]
        ];
    }

    /**
     * @test
     */
    public function member_name_is_not_forbidden_invalid_arguments()
    {
        $data = 666;
        $failureMsg = $this->getPhpunitExceptionText(1, 'string', $data);

        $this->expectException(Exception::class);
        if (!is_null($failureMsg)) {
            $this->expectExceptionMessageRegExp($failureMsg);
        }

        JsonApiAssert::assertIsNotForbiddenMemberName($data);
    }

    /**
     * @test
     */
    public function field_has_no_forbidden_member_name()
    {
        $field = [
            'field' => 'valid'
        ];

        JsonApiAssert::assertFieldHasNoForbiddenMemberName($field);
    }

    /**
     * @test
     * @dataProvider fieldHasForbiddenMemberNameProvider
     */
    public function field_has_forbidden_member_name($data, $failureMessage)
    {
        $fn = function ($data) {
            JsonApiAssert::assertFieldHasNoForbiddenMemberName($data);
        };

        JsonApiAssert::assertTestFail($fn, $failureMessage, $data);
    }

    public function fieldHasForbiddenMemberNameProvider()
    {
        return [
            'direct member' => [
                [
                    'anything' => 'ok',
                    'links' => 'forbidden'
                ],
                Messages::MEMBER_NAME_NOT_ALLOWED
            ],
            'nested member' => [
                [
                    'anything' => 'ok',
                    'something' => [
                        'links' => 'forbidden'
                    ]
                ],
                Messages::MEMBER_NAME_NOT_ALLOWED
            ]
        ];
    }

    /**
     * @test
     * @dataProvider validAttributesObjectProvider
     */
    public function attributes_object_is_valid($json, $strict)
    {
        JsonApiAssert::assertIsValidAttributesObject($json, $strict);
    }

    public function validAttributesObjectProvider()
    {
        return [
            'strict' => [
                [
                    'strict' => 'value'
                ],
                true
            ],
            'not strict' => [
                [
                    'not strict' => 'value'
                ],
                false
            ]
        ];
    }

    /**
     * @test
     * @dataProvider notValidAttributesObjectProvider
     */
    public function attributes_object_is_not_valid($json, $strict, $failureMessage)
    {
        $fn = function ($json, $strict) {
            JsonApiAssert::assertIsValidAttributesObject($json, $strict);
        };

        JsonApiAssert::assertTestFail($fn, $failureMessage, $json, $strict);
    }

    public function notValidAttributesObjectProvider()
    {
        return [
            'key is not valid' => [
                [
                    'key+' => 'value'
                ],
                false,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ],
            'key is not safe' => [
                [
                    'not safe' => 'value'
                ],
                true,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ],
            'field has forbidden member' => [
                [
                    'key' => [
                        'obj' => 'value',
                        'links' => 'forbidden'
                    ]
                ],
                false,
                Messages::MEMBER_NAME_NOT_ALLOWED
            ]
        ];
    }

    /**
     * @test
     * @dataProvider assertIsValidAttributesObjectInvalidArgumentsProvider
     */
    public function assert_is_valid_attributes_object_invalid_arguments($attributes, $strict, $failureMsg)
    {
        $this->expectException(Exception::class);
        if (!is_null($failureMsg)) {
            $this->expectExceptionMessageRegExp($failureMsg);
        }

        JsonApiAssert::assertIsValidAttributesObject($attributes, $strict);
    }

    public function assertIsValidAttributesObjectInvalidArgumentsProvider()
    {
        return [
            'not an array' => [
                'failed',
                false,
                $this->getPhpunitExceptionText(1, 'array', 'failed')
            ]
        ];
    }
}
