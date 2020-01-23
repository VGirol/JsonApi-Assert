<?php

namespace VGirol\JsonApiAssert\Tests\Asserts\Structure;

use VGirol\JsonApiAssert\Assert as JsonApiAssert;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiAssert\Tests\TestCase;
use VGirol\JsonApiConstant\Members;

class ResourceObjectTest extends TestCase
{
    /**
     * @test
     */
    public function resourceFieldNameIsNotForbidden()
    {
        $name = 'test';

        JsonApiAssert::assertIsNotForbiddenResourceFieldName($name);
    }

    /**
     * @test
     * @dataProvider resourceFieldNameIsForbiddenProvider
     */
    public function resourceFieldNameIsForbidden($name, $failureMessage)
    {
        $this->setFailure($failureMessage);
        JsonApiAssert::assertIsNotForbiddenResourceFieldName($name);
    }

    public function resourceFieldNameIsForbiddenProvider()
    {
        return [
            Members::TYPE => [
                Members::TYPE,
                Messages::FIELDS_NAME_NOT_ALLOWED
            ],
            Members::ID => [
                Members::ID,
                Messages::FIELDS_NAME_NOT_ALLOWED
            ]
        ];
    }

    /**
     * @test
     */
    public function resourceLinksObjectIsValid()
    {
        $links = [
            Members::LINK_SELF => 'url'
        ];
        $strict = false;

        JsonApiAssert::assertIsValidResourceLinksObject($links, $strict);
    }

    /**
     * @test
     * @dataProvider notValidResourceLinksObjectProvider
     */
    public function resourceLinksObjectIsNotValid($json, $strict, $failureMessage)
    {
        $this->setFailure($failureMessage);
        JsonApiAssert::assertIsValidResourceLinksObject($json, $strict);
    }

    public function notValidResourceLinksObjectProvider()
    {
        return [
            'not allowed member' => [
                [
                    'anything' => 'not allowed'
                ],
                false,
                Messages::ONLY_ALLOWED_MEMBERS
            ]
        ];
    }

    /**
     * @test
     */
    public function resourceHasValidTopLevelStructure()
    {
        $strict = true;
        $data = [
            Members::ID => '1',
            Members::TYPE => 'articles',
            Members::ATTRIBUTES => [
                'attr' => 'test'
            ],
            Members::LINKS => [
                Members::LINK_SELF => '/articles/1'
            ],
            Members::META => [
                'member' => 'is valid'
            ],
            Members::RELATIONSHIPS => [
                'author' => [
                    Members::LINKS => [
                        Members::LINK_SELF => '/articles/1/relationships/author',
                        Members::LINK_RELATED => '/articles/1/author'
                    ],
                    Members::DATA => [
                        Members::TYPE => 'people',
                        Members::ID => '9'
                    ]
                ]
            ]
        ];

        JsonApiAssert::assertResourceObjectHasValidTopLevelStructure($data, $strict);
    }

    /**
     * @test
     * @dataProvider hasNotValidTopLevelStructureProvider
     */
    public function resourceHasNotValidTopLevelStructure($json, $strict, $failureMessage)
    {
        $this->setFailure($failureMessage);
        JsonApiAssert::assertResourceObjectHasValidTopLevelStructure($json, $strict);
    }

    public function hasNotValidTopLevelStructureProvider()
    {
        return [
            'not an array' => [
                'failed',
                true,
                Messages::RESOURCE_IS_NOT_ARRAY
            ],
            'id is missing' => [
                [
                    Members::TYPE => 'test',
                    Members::ATTRIBUTES => [
                        'attr' => 'value'
                    ]
                ],
                true,
                Messages::RESOURCE_ID_MEMBER_IS_ABSENT
            ],
            'type is missing' => [
                [
                    Members::ID => '1',
                    Members::ATTRIBUTES => [
                        'attr' => 'value'
                    ]
                ],
                true,
                Messages::RESOURCE_TYPE_MEMBER_IS_ABSENT
            ],
            'missing mandatory member' => [
                [
                    Members::ID => '1',
                    Members::TYPE => 'test'
                ],
                true,
                sprintf(
                    Messages::CONTAINS_AT_LEAST_ONE,
                    implode(', ', [Members::ATTRIBUTES, Members::RELATIONSHIPS, Members::LINKS, Members::META])
                )
            ],
            'member not allowed' => [
                [
                    Members::ID => '1',
                    Members::TYPE => 'test',
                    Members::META => [
                        'anything' => 'good'
                    ],
                    'wrong' => 'wrong'
                ],
                true,
                Messages::ONLY_ALLOWED_MEMBERS
            ]
        ];
    }

    /**
     * @test
     */
    public function resourceIdMemberIsValid()
    {
        $data = [
            Members::ID => '1',
            Members::TYPE => 'test'
        ];

        JsonApiAssert::assertResourceIdMember($data);
    }

    /**
     * @test
     * @dataProvider notValidResourceIdMemberProvider
     */
    public function resourceIdMemberIsNotValid($json, $failureMessage)
    {
        $this->setFailure($failureMessage);
        JsonApiAssert::assertResourceIdMember($json);
    }

    public function notValidResourceIdMemberProvider()
    {
        return [
            'id is empty' => [
                [
                    Members::ID => '',
                    Members::TYPE => 'test'
                ],
                Messages::RESOURCE_ID_MEMBER_IS_EMPTY
            ],
            'id is not a string' => [
                [
                    Members::ID => 1,
                    Members::TYPE => 'test'
                ],
                Messages::RESOURCE_ID_MEMBER_IS_NOT_STRING
            ]
        ];
    }

    /**
     * @test
     */
    public function resourceTypeMemberIsValid()
    {
        $data = [
            Members::ID => '1',
            Members::TYPE => 'test'
        ];
        $strict = false;

        JsonApiAssert::assertResourceTypeMember($data, $strict);
    }

    /**
     * @test
     * @dataProvider notValidResourceTypeMemberProvider
     */
    public function resourceTypeMemberIsNotValid($json, $strict, $failureMessage)
    {
        $this->setFailure($failureMessage);
        JsonApiAssert::assertResourceTypeMember($json, $strict);
    }

    public function notValidResourceTypeMemberProvider()
    {
        return [
            'type is empty' => [
                [
                    Members::ID => '1',
                    Members::TYPE => ''
                ],
                false,
                Messages::RESOURCE_TYPE_MEMBER_IS_EMPTY
            ],
            'type is not a string' => [
                [
                    Members::ID => '1',
                    Members::TYPE => 404
                ],
                false,
                Messages::RESOURCE_TYPE_MEMBER_IS_NOT_STRING
            ],
            'type value has forbidden characters' => [
                [
                    Members::ID => '1',
                    Members::TYPE => 'test+1'
                ],
                false,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ],
            'type value has not safe characters' => [
                [
                    Members::ID => '1',
                    Members::TYPE => 'test 1'
                ],
                true,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ]
        ];
    }

    /**
     * @test
     */
    public function resourceFieldIsValid()
    {
        $data = [
            Members::ID => '1',
            Members::TYPE => 'articles',
            Members::ATTRIBUTES => [
                'attr' => 'test'
            ],
            Members::RELATIONSHIPS => [
                'author' => [
                    Members::DATA => [
                        Members::TYPE => 'people',
                        Members::ID => '9'
                    ]
                ]
            ]
        ];

        JsonApiAssert::assertHasValidFields($data);
    }

    /**
     * @test
     * @dataProvider isNotValidResourceFieldProvider
     */
    public function resourceFieldIsNotValid($json, $failureMessage)
    {
        $this->setFailure($failureMessage);
        JsonApiAssert::assertHasValidFields($json);
    }

    public function isNotValidResourceFieldProvider()
    {
        return [
            'attribute and relationship with the same name' => [
                [
                    Members::ID => '1',
                    Members::TYPE => 'articles',
                    Members::ATTRIBUTES => [
                        'anonymous' => 'test'
                    ],
                    Members::RELATIONSHIPS => [
                        'anonymous' => [
                            Members::DATA => [
                                Members::TYPE => 'people',
                                Members::ID => '9'
                            ]
                        ]
                    ]
                ],
                Messages::FIELDS_HAVE_SAME_NAME
            ],
            'attribute named type or id' => [
                [
                    Members::ID => '1',
                    Members::TYPE => 'articles',
                    Members::ATTRIBUTES => [
                        'attr' => 'test',
                        Members::ID => 'not valid'
                    ]
                ],
                Messages::FIELDS_NAME_NOT_ALLOWED
            ],
            'relationship named type or id' => [
                [
                    Members::ID => '1',
                    Members::TYPE => 'articles',
                    Members::ATTRIBUTES => [
                        'attr' => 'test'
                    ],
                    Members::RELATIONSHIPS => [
                        Members::TYPE => [
                            Members::DATA => [
                                Members::TYPE => 'people',
                                Members::ID => '9'
                            ]
                        ]
                    ]
                ],
                Messages::FIELDS_NAME_NOT_ALLOWED
            ]
        ];
    }

    /**
     * @test
     */
    public function resourceObjectIsValid()
    {
        $data = [
            Members::ID => '1',
            Members::TYPE => 'articles',
            Members::ATTRIBUTES => [
                'attr' => 'test'
            ],
            Members::LINKS => [
                Members::LINK_SELF => '/articles/1'
            ],
            Members::META => [
                'member' => 'is valid'
            ],
            Members::RELATIONSHIPS => [
                'author' => [
                    Members::LINKS => [
                        Members::LINK_SELF => '/articles/1/relationships/author',
                        Members::LINK_RELATED => '/articles/1/author'
                    ],
                    Members::DATA => [
                        Members::TYPE => 'people',
                        Members::ID => '9'
                    ]
                ]
            ]
        ];
        $strict = false;

        JsonApiAssert::assertIsValidResourceObject($data, $strict);
    }

    /**
     * @test
     * @dataProvider isNotValidResourceObjectProvider
     */
    public function resourceObjectIsNotValid($json, $strict, $failureMessage)
    {
        $this->setFailure($failureMessage);
        JsonApiAssert::assertIsValidResourceObject($json, $strict);
    }

    public function isNotValidResourceObjectProvider()
    {
        return [
            'not an array' => [
                'failed',
                false,
                Messages::RESOURCE_IS_NOT_ARRAY
            ],
            'id is not valid' => [
                [
                    Members::ID => 1,
                    Members::TYPE => 'test',
                    Members::ATTRIBUTES => [
                        'attr' => 'test'
                    ]
                ],
                false,
                Messages::RESOURCE_ID_MEMBER_IS_NOT_STRING
            ],
            'type is not valid' => [
                [
                    Members::ID => '1',
                    Members::TYPE => 404,
                    Members::ATTRIBUTES => [
                        'attr' => 'test'
                    ]
                ],
                false,
                Messages::RESOURCE_TYPE_MEMBER_IS_NOT_STRING
            ],
            'missing mandatory member' => [
                [
                    Members::ID => '1',
                    Members::TYPE => 'test'
                ],
                false,
                sprintf(
                    Messages::CONTAINS_AT_LEAST_ONE,
                    implode(', ', [Members::ATTRIBUTES, Members::RELATIONSHIPS, Members::LINKS, Members::META])
                )
            ],
            'member not allowed' => [
                [
                    Members::ID => '1',
                    Members::TYPE => 'test',
                    Members::ATTRIBUTES => [
                        'attr' => 'test'
                    ],
                    'wrong' => 'wrong'
                ],
                false,
                Messages::ONLY_ALLOWED_MEMBERS
            ],
            'attributes not valid' => [
                [
                    Members::ID => '1',
                    Members::TYPE => 'test',
                    Members::ATTRIBUTES => [
                        'attr' => 'test',
                        'key+' => 'wrong'
                    ]
                ],
                false,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ],
            'fields not valid (attribute and relationship with the same name)' => [
                [
                    Members::ID => '1',
                    Members::TYPE => 'articles',
                    Members::ATTRIBUTES => [
                        'anonymous' => 'test'
                    ],
                    Members::RELATIONSHIPS => [
                        'anonymous' => [
                            Members::DATA => [
                                Members::TYPE => 'people',
                                Members::ID => '9'
                            ]
                        ]
                    ]
                ],
                false,
                Messages::FIELDS_HAVE_SAME_NAME
            ],
            'fields not valid (attribute named "type" or "id")' => [
                [
                    Members::ID => '1',
                    Members::TYPE => 'articles',
                    Members::ATTRIBUTES => [
                        'attr' => 'test',
                        Members::ID => 'not valid'
                    ]
                ],
                false,
                Messages::FIELDS_NAME_NOT_ALLOWED
            ],
            'relationship not valid' => [
                [
                    Members::ID => '1',
                    Members::TYPE => 'articles',
                    Members::ATTRIBUTES => [
                        'attr' => 'test'
                    ],
                    Members::RELATIONSHIPS => [
                        'author' => [
                            Members::DATA => [
                                Members::TYPE => 'people',
                                Members::ID => '9',
                                'wrong' => 'not valid'
                            ]
                        ]
                    ]
                ],
                false,
                Messages::ONLY_ALLOWED_MEMBERS
            ],
            'meta with not safe member name' => [
                [
                    Members::ID => '1',
                    Members::TYPE => 'articles',
                    Members::ATTRIBUTES => [
                        'attr' => 'test'
                    ],
                    Members::META => [
                        'not valid' => 'due to the blank character'
                    ]
                ],
                true,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ],
            'links not valid' => [
                [
                    Members::ID => '1',
                    Members::TYPE => 'articles',
                    Members::ATTRIBUTES => [
                        'attr' => 'test'
                    ],
                    Members::LINKS => [
                        'not valid' => 'bad'
                    ]
                ],
                true,
                Messages::ONLY_ALLOWED_MEMBERS
            ]
        ];
    }

    /**
     * @test
     */
    public function emptyResourceObjectCollectionIsValid()
    {
        $data = [];
        $strict = false;

        JsonApiAssert::assertIsValidResourceObjectCollection($data, $strict);
    }

    /**
     * @test
     */
    public function resourceObjectCollectionIsValid()
    {
        $data = [];
        for ($i = 1; $i < 3; $i++) {
            $data[] = [
                Members::ID => (string) $i,
                Members::TYPE => 'articles',
                Members::ATTRIBUTES => [
                    'attr' => 'test'
                ]
            ];
        }
        $strict = false;

        JsonApiAssert::assertIsValidResourceObjectCollection($data, $strict);
    }

    /**
     * @test
     * @dataProvider resourceObjectCollectionIsNotValidProvider
     */
    public function resourceObjectCollectionIsNotValid($json, $strict, $failureMessage)
    {
        $this->setFailure($failureMessage);
        JsonApiAssert::assertIsValidResourceObjectCollection($json, $strict);
    }

    public function resourceObjectCollectionIsNotValidProvider()
    {
        return [
            'not an array' => [
                'failed',
                false,
                Messages::RESOURCE_COLLECTION_NOT_ARRAY
            ],
            'not an array of objects' => [
                [
                    Members::ID => '1',
                    Members::TYPE => 'articles',
                    Members::ATTRIBUTES => [
                        'attr' => 'test'
                    ]
                ],
                false,
                Messages::MUST_BE_ARRAY_OF_OBJECTS
            ],
            'not valid collection' => [
                [
                    [
                        Members::ID => 1,
                        Members::TYPE => 'articles',
                        Members::ATTRIBUTES => [
                            'attr' => 'test'
                        ]
                    ]
                ],
                false,
                Messages::RESOURCE_ID_MEMBER_IS_NOT_STRING
            ]
        ];
    }
}
