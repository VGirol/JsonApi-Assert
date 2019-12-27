<?php

namespace VGirol\JsonApiAssert\Tests\Asserts\Structure;

use VGirol\JsonApiAssert\Assert as JsonApiAssert;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiAssert\Tests\TestCase;
use VGirol\JsonApiConstant\Members;

class TopLevelMembersTest extends TestCase
{
    /**
     * @test
     */
    public function documentHasValidTopLevelMembers()
    {
        $data = [
            Members::LINKS => [
                Members::LINK_SELF => 'http://example.com/articles'
            ],
            Members::DATA => [
                [
                    Members::TYPE => 'articles',
                    Members::ID => '1',
                    Members::ATTRIBUTES => [
                        Members::ERROR_TITLE => 'First'
                    ]
                ],
                [
                    Members::TYPE => 'articles',
                    Members::ID => '2',
                    Members::ATTRIBUTES => [
                        Members::ERROR_TITLE => 'Second'
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
        $this->setFailure($failureMessage);
        JsonApiAssert::assertHasValidTopLevelMembers($data);
    }

    public function notValidTopLevelMembersProvider()
    {
        return [
            'miss mandatory members' => [
                [
                    Members::LINKS => [
                        Members::LINK_SELF => 'http://example.com/articles'
                    ]
                ],
                sprintf(Messages::TOP_LEVEL_MEMBERS, implode('", "', [Members::DATA, Members::ERRORS, Members::META]))
            ],
            'data and error incompatible' => [
                [
                    Members::ERRORS => [
                        [
                            Members::ERROR_CODE => 'E13'
                        ]
                    ],
                    Members::DATA => [
                        Members::TYPE => 'articles',
                        Members::ID => '1',
                        Members::ATTRIBUTES => [
                            Members::ERROR_TITLE => 'JSON:API paints my bikeshed!'
                        ]
                    ]
                ],
                Messages::TOP_LEVEL_DATA_AND_ERROR
            ],
            'only allowed members' => [
                [
                    Members::DATA => [
                        Members::TYPE => 'articles',
                        Members::ID => '1',
                        Members::ATTRIBUTES => [
                            Members::ERROR_TITLE => 'JSON:API paints my bikeshed!'
                        ]
                    ],
                    'anything' => 'not allowed'
                ],
                Messages::ONLY_ALLOWED_MEMBERS
            ],
            'no data but included' => [
                [
                    Members::INCLUDED => [
                        [
                            Members::TYPE => 'articles',
                            Members::ID => '1',
                            Members::ATTRIBUTES => [
                                Members::ERROR_TITLE => 'JSON:API paints my bikeshed!'
                            ]
                        ]
                    ],
                    Members::META => [
                        'anything' => 'ok'
                    ]
                ],
                Messages::TOP_LEVEL_DATA_AND_INCLUDED
            ]
        ];
    }
}
