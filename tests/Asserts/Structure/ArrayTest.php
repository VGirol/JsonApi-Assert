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
                        'key1' => 'value1'
                    ],
                    [
                        'key2' => 'value2'
                    ]
                ]
            ]
        ];
    }

    /**
     * @test
     * @dataProvider assertIsArrayOfObjectsFailedProvider
     */
    public function assertIsArrayOfObjectsFailed($data, $message, $failureMessage)
    {
        $this->setFailure($failureMessage);
        JsonApiAssert::assertIsArrayOfObjects($data, $message);
    }

    public function assertIsArrayOfObjectsFailedProvider()
    {
        return [
            'associative array' => [
                [
                    'key1' => 'value1',
                    'key2' => 'value2'
                ],
                null,
                Messages::MUST_BE_ARRAY_OF_OBJECTS
            ],
            'customized message' => [
                [
                    'key1' => 'value1',
                    'key2' => 'value2'
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
            'key1' => 'value1',
            'key2' => 'value2'
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
                'key1' => 'value1',
            ],
            [
                'key2' => 'value2'
            ]
        ];
        $failureMessage = Messages::MUST_NOT_BE_ARRAY_OF_OBJECTS;

        $this->setFailure($failureMessage);
        JsonApiAssert::assertIsNotArrayOfObjects($data);
    }

    /**
     * @test
     */
    public function assertIsNotArrayOfObjectsWithInvalidArguments()
    {
        $json = null;

        $this->setInvalidArgumentException(1, 'array', $json);

        JsonApiAssert::assertIsNotArrayOfObjects($json);
    }
}
