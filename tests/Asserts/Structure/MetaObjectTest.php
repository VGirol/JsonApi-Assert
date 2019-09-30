<?php
namespace VGirol\JsonApiAssert\Tests\Asserts\Structure;

use VGirol\JsonApiAssert\Assert as JsonApiAssert;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiAssert\Tests\TestCase;

class MetaObjectTest extends TestCase
{
    /**
     * @test
     */
    public function metaObjectIsValid()
    {
        $data = [
            'key' => 'value',
            'another' => 'member'
        ];
        $strict = false;

        JsonApiAssert::assertIsValidMetaObject($data, $strict);
    }

    /**
     * @test
     * @dataProvider notValidMetaObjectProvider
     */
    public function metaObjectIsNotValid($json, $strict, $failureMessage)
    {
        $this->setFailure($failureMessage);
        JsonApiAssert::assertIsValidMetaObject($json, $strict);
    }

    public function notValidMetaObjectProvider()
    {
        return [
            'not an associative array' => [
                [
                    [
                        'key' => 'failed'
                    ]
                ],
                false,
                Messages::META_OBJECT_IS_NOT_ARRAY
            ],
            'array of objects' => [
                [
                    [ 'first' => 'element' ],
                    [ 'second' => 'element' ]
                ],
                false,
                Messages::META_OBJECT_IS_NOT_ARRAY
            ],
            'key is not valid' => [
                [
                    'key+' => 'value'
                ],
                false,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ],
            'key is not safe' => [
                [
                    'not valid' => 'due to the blank character'
                ],
                true,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ]
        ];
    }
}
