<?php

namespace VGirol\JsonApiAssert\Tests\Asserts\Structure;

use VGirol\JsonApiAssert\Assert as JsonApiAssert;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiAssert\Tests\TestCase;
use VGirol\JsonApiConstant\Members;

class AttributesObjectTest extends TestCase
{
    /**
     * @test
     */
    public function memberNameIsNotForbidden()
    {
        $name = 'valid';
        JsonApiAssert::assertIsNotForbiddenMemberName($name);
    }

    /**
     * @test
     * @dataProvider forbiddenMemberNameProvider
     */
    public function memberNameIsForbidden($data, $failureMessage)
    {
        $this->setAssertionFailure($failureMessage);
        JsonApiAssert::assertIsNotForbiddenMemberName($data);
    }

    public function forbiddenMemberNameProvider()
    {
        return [
            'member named "relationships"' => [
                Members::RELATIONSHIPS,
                Messages::MEMBER_NAME_NOT_ALLOWED
            ],
            'member named "links"' => [
                Members::LINKS,
                Messages::MEMBER_NAME_NOT_ALLOWED
            ]
        ];
    }

    /**
     * @test
     */
    public function assertIsNotForbiddenMemberNameWithInvalidArguments()
    {
        $data = 666;

        $this->setInvalidArgumentException(1, 'string', $data);

        JsonApiAssert::assertIsNotForbiddenMemberName($data);
    }

    /**
     * @test
     */
    public function fieldHasNoForbiddenMemberName()
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
    public function fieldHasForbiddenMemberName($data, $failureMessage)
    {
        $this->setAssertionFailure($failureMessage);
        JsonApiAssert::assertFieldHasNoForbiddenMemberName($data);
    }

    public function fieldHasForbiddenMemberNameProvider()
    {
        return [
            'direct member' => [
                [
                    'anything' => 'ok',
                    Members::LINKS => 'forbidden'
                ],
                Messages::MEMBER_NAME_NOT_ALLOWED
            ],
            'nested member' => [
                [
                    'anything' => 'ok',
                    'something' => [
                        Members::LINKS => 'forbidden'
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
    public function attributesObjectIsValid($json, $strict)
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
    public function attributesObjectIsNotValid($json, $strict, $failureMessage)
    {
        $this->setAssertionFailure($failureMessage);
        JsonApiAssert::assertIsValidAttributesObject($json, $strict);
    }

    public function notValidAttributesObjectProvider()
    {
        return [
            'key is not valid' => [
                [
                    'key+' => 'value'
                ],
                false,
                Messages::MEMBER_NAME_MUST_NOT_HAVE_RESERVED_CHARACTERS
            ],
            'key is not safe' => [
                [
                    'not safe' => 'value'
                ],
                true,
                Messages::MEMBER_NAME_MUST_NOT_HAVE_RESERVED_CHARACTERS
            ],
            'field has forbidden member' => [
                [
                    'key' => [
                        'obj' => 'value',
                        Members::LINKS => 'forbidden'
                    ]
                ],
                false,
                Messages::MEMBER_NAME_NOT_ALLOWED
            ]
        ];
    }

    /**
     * @test
     */
    public function assertIsValidAttributesObjectWithInvalidArguments()
    {
        $attributes = 'failed';
        $strict = false;

        $this->setInvalidArgumentException(1, 'array', $attributes);

        JsonApiAssert::assertIsValidAttributesObject($attributes, $strict);
    }
}
