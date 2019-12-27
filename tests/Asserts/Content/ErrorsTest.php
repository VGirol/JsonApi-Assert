<?php

namespace VGirol\JsonApiAssert\Tests\Asserts\Content;

use VGirol\JsonApiAssert\Assert;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiAssert\Tests\TestCase;
use VGirol\JsonApiConstant\Members;

class ErrorsTest extends TestCase
{
    /**
     * @test
     */
    public function errorsContains()
    {
        $expectedErrors = [
            [
                Members::ERROR_STATUS => '415',
                Members::ERROR_TITLE => 'Not Acceptable',
                Members::ERROR_DETAILS => 'description'
            ]
        ];
        $errors = [
            [
                Members::ERROR_STATUS => '409',
                Members::ERROR_TITLE => 'Conflict',
                Members::ERROR_DETAILS => 'description',
                Members::META => [
                    'trace' => [
                        [
                            'file' => 'path'
                        ]
                    ]
                ]
            ],
            [
                Members::ERROR_STATUS => '415',
                Members::ERROR_TITLE => 'Not Acceptable',
                Members::ERROR_DETAILS => 'description',
                Members::META => [
                    'trace' => [
                        [
                            'file' => 'path'
                        ]
                    ]
                ]
            ]
        ];

        Assert::assertErrorsContains($expectedErrors, $errors, false);
    }

    /**
     * @test
     * @dataProvider errorsContainsFailedProvider
     */
    public function errorsContainsFailed($expectedErrors, $errors, $strict, $failureMsg)
    {
        $this->setFailure($failureMsg);

        Assert::assertErrorsContains($expectedErrors, $errors, $strict);
    }

    public function errorsContainsFailedProvider()
    {
        return [
            'errors object is not valid' => [
                [
                    [
                        Members::ERROR_STATUS => '415',
                        Members::ERROR_TITLE => 'Not Acceptable',
                        Members::ERROR_DETAILS => 'description'
                    ]
                ],
                [
                    [
                        Members::ERROR_STATUS => 415,
                        Members::ERROR_TITLE => 'Not Acceptable',
                        Members::ERROR_DETAILS => 'description'
                    ]
                ],
                false,
                Messages::ERROR_STATUS_IS_NOT_STRING
            ],
            'not enough errors' => [
                [
                    [
                        Members::ERROR_STATUS => '409',
                        Members::ERROR_TITLE => 'Conflict',
                        Members::ERROR_DETAILS => 'description'
                    ],
                    [
                        Members::ERROR_STATUS => '409',
                        Members::ERROR_TITLE => 'Conflict',
                        Members::ERROR_DETAILS => 'description'
                    ]
                ],
                [
                    [
                        Members::ERROR_STATUS => '409',
                        Members::ERROR_TITLE => 'Conflict',
                        Members::ERROR_DETAILS => 'description'
                    ]
                ],
                false,
                Messages::ERRORS_OBJECT_CONTAINS_NOT_ENOUGH_ERRORS
            ],
            'expected error not present' => [
                [
                    [
                        Members::ERROR_STATUS => '409',
                        Members::ERROR_TITLE => 'Conflict',
                        Members::ERROR_DETAILS => 'description'
                    ]
                ],
                [
                    [
                        Members::ERROR_STATUS => '406',
                        Members::ERROR_TITLE => 'Not Acceptable',
                        Members::ERROR_DETAILS => 'description'
                    ],
                    [
                        Members::ERROR_STATUS => '415',
                        Members::ERROR_TITLE => 'Not Acceptable',
                        Members::ERROR_DETAILS => 'description'
                    ]
                ],
                false,
                sprintf(
                    Messages::ERRORS_OBJECT_DOES_NOT_CONTAIN_EXPECTED_ERROR,
                    var_export(
                        [
                            Members::ERROR_STATUS => '409',
                            Members::ERROR_TITLE => 'Conflict',
                            Members::ERROR_DETAILS => 'description'
                        ],
                        true
                    ),
                    var_export(
                        [
                            Members::ERROR_STATUS => '409',
                            Members::ERROR_TITLE => 'Conflict',
                            Members::ERROR_DETAILS => 'description'
                        ],
                        true
                    )
                )
            ],
            'expected error not the same' => [
                [
                    [
                        Members::ID => '6',
                        Members::ERROR_STATUS => '409',
                        Members::ERROR_TITLE => 'Conflict',
                        Members::ERROR_DETAILS => 'description is different'
                    ]
                ],
                [
                    [
                        Members::ERROR_STATUS => '406',
                        Members::ERROR_TITLE => 'Not Acceptable',
                        Members::ERROR_DETAILS => 'description'
                    ],
                    [
                        Members::ID => '6',
                        Members::ERROR_STATUS => '409',
                        Members::ERROR_TITLE => 'Conflict',
                        Members::ERROR_DETAILS => 'description'
                    ]
                ],
                false,
                $this->formatAsRegex(Messages::ERRORS_OBJECT_DOES_NOT_CONTAIN_EXPECTED_ERROR)
            ]
        ];
    }

    /**
     * @test
     */
    public function errorsContainsInvalidArguments()
    {
        $expectedErrors = [
            [
                Members::ERROR_STATUS => 415,
                Members::ERROR_TITLE => 'Not Acceptable',
                Members::ERROR_DETAILS => 'description'
            ]
        ];
        $errors = [
            [
                Members::ERROR_STATUS => '415',
                Members::ERROR_TITLE => 'Not Acceptable',
                Members::ERROR_DETAILS => 'description'
            ]
        ];
        $strict = false;
        $this->setInvalidArgumentException(1, 'errors object', $expectedErrors);

        Assert::assertErrorsContains($expectedErrors, $errors, $strict);
    }
}
