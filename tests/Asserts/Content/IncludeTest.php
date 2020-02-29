<?php

namespace VGirol\JsonApiAssert\Tests\Asserts\Content;

use VGirol\JsonApiAssert\Assert;
use VGirol\JsonApiAssert\Tests\TestCase;
use VGirol\JsonApiConstant\Members;

class IncludeTest extends TestCase
{
    /**
     * @test
     */
    public function includeObjectContains()
    {
        $expected = [
            Members::ID => '123',
            Members::TYPE => 'test',
            Members::ATTRIBUTES => [
                'attr1' => 'value1'
            ]
        ];
        $json = [
            [
                Members::ID => '456',
                Members::TYPE => 'test',
                Members::ATTRIBUTES => [
                    'attr2' => 'value2'
                ]
            ],
            [
                Members::ID => '123',
                Members::TYPE => 'test',
                Members::ATTRIBUTES => [
                    'attr1' => 'value1'
                ]
            ]
        ];

        Assert::assertIncludeObjectContains($expected, $json);
    }

    /**
     * @test
     */
    public function includeObjectContainsFailed()
    {
        $expected = [
            Members::ID => '123',
            Members::TYPE => 'test',
            Members::ATTRIBUTES => [
                'attr1' => 'value1'
            ]
        ];
        $json = [
            [
                Members::ID => '456',
                Members::TYPE => 'test',
                Members::ATTRIBUTES => [
                    'attr2' => 'value2'
                ]
            ],
            [
                Members::ID => '789',
                Members::TYPE => 'test',
                Members::ATTRIBUTES => [
                    'attr3' => 'value3'
                ]
            ]
        ];

        $this->setAssertionFailure();

        Assert::assertIncludeObjectContains($expected, $json);
    }
}
