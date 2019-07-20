<?php

namespace VGirol\JsonApiAssert\Tests\Asserts\Content;

use VGirol\JsonApiAssert\Assert;
use VGirol\JsonApiAssert\Tests\TestCase;

class JsonapiTest extends TestCase
{
    /**
     * @test
     */
    public function jsonapiObjectEquals()
    {
        $expected = [
            'version' => '1.0'
        ];
        $json = [
            'version' => '1.0'
        ];

        Assert::assertJsonapiObjectEquals($expected, $json);
    }

    /**
     * @test
     */
    public function jsonapiObjectEqualsFailed()
    {
        $expected = [
            'version' => '1.0'
        ];
        $json = [
            'meta' => [
                'nothing' => 'nothing'
            ]
        ];

        $this->setFailureException();

        Assert::assertJsonapiObjectEquals($expected, $json);
    }
}
