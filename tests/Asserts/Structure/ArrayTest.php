<?php
namespace VGirol\JsonApiAssert\Tests\Asserts\Structure;

use VGirol\JsonApiAssert\Assert as JsonApiAssert;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiAssert\Tests\TestCase;

class ArrayTest extends TestCase
{
    /**
     * @test
     * @dataProvider arrayOfObjectsProvider
     */
    public function assertIsArrayOfObjects($json)
    {
        JsonApiAssert::assertIsArrayOfObjects($json);
    }

    public function arrayOfObjectsProvider()
    {
        return [
            'empty array' => [
                []
            ],
            'filled array' => [
                [
                    [
                        'meta' => 'valid'
                    ],
                    [
                        'first' => 'jsonapi'
                    ]
                ]
            ]
        ];
    }

    /**
     * @test
     * @dataProvider notArrayOfObjectsProvider
     */
    public function assertIsArrayOfObjectsFailed($data, $message, $failureMessage)
    {
        $this->setFailureException($failureMessage);
        JsonApiAssert::assertIsArrayOfObjects($data, $message);
    }

    public function notArrayOfObjectsProvider()
    {
        return [
            'associative array' => [
                [
                    'meta' => 'valid',
                    'errors' => 'jsonapi'
                ],
                null,
                Messages::MUST_BE_ARRAY_OF_OBJECTS
            ],
            'customized message' => [
                [
                    'meta' => 'valid',
                    'errors' => 'jsonapi'
                ],
                'customized message',
                'customized message'
            ]
        ];
    }

    /**
     * @test
     */
    public function assertIsArrayOfObjectsWithInvalidArguments()
    {

        $json = 'invalid';

        $this->setInvalidArgumentException(1, 'array', $json);

        JsonApiAssert::assertIsArrayOfObjects($json);
    }

    /**
     * @test
     */
    public function assertIsNotArrayOfObjects()
    {
        $data = [
            'meta' => 'valid',
            'first' => 'jsonapi'
        ];

        JsonApiAssert::assertIsNotArrayOfObjects($data);
    }

    /**
     * @test
     */
    public function assertIsNotArrayOfObjectsFailed()
    {
        $data = [
            [
                'meta' => 'valid'
            ],
            [
                'first' => 'jsonapi'
            ]
        ];
        $failureMessage = null;

        $this->setFailureException($failureMessage);
        JsonApiAssert::assertIsNotArrayOfObjects($data);
    }
}
