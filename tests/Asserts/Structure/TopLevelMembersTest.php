<?php
namespace VGirol\JsonApiAssert\Tests\Asserts\Structure;

use VGirol\JsonApiAssert\Assert as JsonApiAssert;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiAssert\Tests\TestCase;

class TopLevelMembersTest extends TestCase
{
    /**
     * @test
     */
    public function documentHasValidTopLevelMembers()
    {
        $data = [
            'links' => [
                'self' => 'http://example.com/articles'
            ],
            'data' => [
                [
                    'type' => 'articles',
                    'id' => '1',
                    'attributes' => [
                        'title' => 'First'
                    ]
                ],
                [
                    'type' => 'articles',
                    'id' => '2',
                    'attributes' => [
                        'title' => 'Second'
                    ]
                ]
            ]
        ];

        JsonApiAssert::assertHasValidTopLevelMembers($data);
    }

    /**
     * @test
     * @dataProvider notValidTopLevelMembersProvider
     */
    public function documentHasNotValidTopLevelMembers($data, $failureMessage)
    {
        $this->setFailureException($failureMessage);
        JsonApiAssert::assertHasValidTopLevelMembers($data);
    }

    public function notValidTopLevelMembersProvider()
    {
        return [
            'miss mandatory members' => [
                [
                    'links' => [
                        'self' => 'http://example.com/articles'
                    ]
                ],
                sprintf(Messages::TOP_LEVEL_MEMBERS, implode('", "', ['data', 'errors', 'meta']))
            ],
            'data and error incompatible' => [
                [
                    'errors' => [
                        [
                            'code' => 'E13'
                        ]
                    ],
                    'data' => [
                        'type' => 'articles',
                        'id' => '1',
                        'attributes' => [
                            'title' => 'JSON:API paints my bikeshed!'
                        ]
                    ]
                ],
                Messages::TOP_LEVEL_DATA_AND_ERROR
            ],
            'only allowed members' => [
                [
                    'data' => [
                        'type' => 'articles',
                        'id' => '1',
                        'attributes' => [
                            'title' => 'JSON:API paints my bikeshed!'
                        ]
                    ],
                    'anything' => 'not allowed'
                ],
                Messages::ONLY_ALLOWED_MEMBERS
            ],
            'no data but included' => [
                [
                    'included' => 'not allowed',
                    'meta' => [
                        'anything' => 'ok'
                    ]
                ],
                Messages::TOP_LEVEL_DATA_AND_INCLUDED
            ]
        ];
    }
}
