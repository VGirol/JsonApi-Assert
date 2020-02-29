<?php
namespace VGirol\JsonApiAssert\Tests\Asserts\Structure;

use VGirol\JsonApiAssert\Assert as JsonApiAssert;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiAssert\Tests\TestCase;

class MemberNameTest extends TestCase
{
    /**
     * @test
     * @dataProvider validMemberNameProvider
     */
    public function memberNameIsValid($data, $strict)
    {
        JsonApiAssert::assertIsValidMemberName($data, $strict);
    }

    public function validMemberNameProvider()
    {
        return [
            'not strict' => [
                'valid member',
                false
            ],
            'strict' => [
                'valid-member',
                true
            ],
            'short' => [
                'a',
                true
            ]
        ];
    }

    /**
     * @test
     * @dataProvider notValidMemberNameProvider
     */
    public function memberNameIsNotValid($data, $strict, $failureMessage)
    {
        $this->setAssertionFailure($failureMessage);
        JsonApiAssert::assertIsValidMemberName($data, $strict);
    }

    public function notValidMemberNameProvider()
    {
        return [
            'not a string' => [
                123,
                false,
                Messages::MEMBER_NAME_MUST_BE_STRING
            ],
            'too short' => [
                '',
                false,
                Messages::MEMBER_NAME_IS_TOO_SHORT
            ],
            'strict mode' => [
                'not valid',
                true,
                Messages::MEMBER_NAME_MUST_NOT_HAVE_RESERVED_CHARACTERS
            ],
            'reserved characters' => [
                'az-F%3_t',
                false,
                Messages::MEMBER_NAME_MUST_NOT_HAVE_RESERVED_CHARACTERS
            ],
            'start with not globally allowed character' => [
                '_az',
                false,
                Messages::MEMBER_NAME_MUST_START_AND_END_WITH_ALLOWED_CHARACTERS
            ],
            'end with not globally allowed character' => [
                'az_',
                false,
                Messages::MEMBER_NAME_MUST_START_AND_END_WITH_ALLOWED_CHARACTERS
            ]
        ];
    }
}
