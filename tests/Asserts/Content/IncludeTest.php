<?php

namespace VGirol\JsonApiAssert\Tests\Asserts\Content;

use VGirol\JsonApiAssert\Assert;
use VGirol\JsonApiAssert\Tests\TestCase;

class IncludeTest extends TestCase
{
    /**
     * @test
     */
    public function includeObjectContains()
    {
        $expected = [
            'id' => '123',
            'type' => 'test',
            'attributes' => [
                'attr1' => 'value1'
            ]
        ];
        $json = [
            [
                'id' => '456',
                'type' => 'test',
                'attributes' => [
                    'attr2' => 'value2'
                ]
            ],
            [
                'id' => '123',
                'type' => 'test',
                'attributes' => [
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
            'id' => '123',
            'type' => 'test',
            'attributes' => [
                'attr1' => 'value1'
            ]
        ];
        $json = [
            [
                'id' => '456',
                'type' => 'test',
                'attributes' => [
                    'attr2' => 'value2'
                ]
            ],
            [
                'id' => '789',
                'type' => 'test',
                'attributes' => [
                    'attr3' => 'value3'
                ]
            ]
        ];

        $this->setFailure();

        Assert::assertIncludeObjectContains($expected, $json);
    }
}
