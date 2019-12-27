<?php

namespace VGirol\JsonApiAssert\Tests\Asserts\Content;

use VGirol\JsonApiAssert\Assert;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiAssert\Tests\TestCase;
use VGirol\JsonApiConstant\Members;

class JsonapiTest extends TestCase
{
    /**
     * @test
     */
    public function jsonapiObjectEquals()
    {
        $expected = [
            Members::JSONAPI_VERSION => '1.0'
        ];
        $json = [
            Members::JSONAPI_VERSION => '1.0'
        ];

        Assert::assertJsonapiObjectEquals($expected, $json);
    }

    /**
     * @test
     */
    public function jsonapiObjectEqualsFailed()
    {
        $expected = [
            Members::JSONAPI_VERSION => '1.0'
        ];
        $json = [
            Members::META => [
                'nothing' => 'nothing'
            ]
        ];

        $this->setFailure($this->formatAsRegex(Messages::JSONAPI_OBJECT_NOT_EQUAL));

        Assert::assertJsonapiObjectEquals($expected, $json);
    }
}
