<?php
namespace VGirol\JsonApiAssert\Tests\Asserts\Structure;

use VGirol\JsonApiAssert\Assert as JsonApiAssert;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiAssert\Tests\TestCase;
use VGirol\JsonApiConstant\Members;

class MembersTest extends TestCase
{
    /**
     * @test
     */
    public function assertHasMember()
    {
        $json = [
            Members::META => ['key' => 'value'],
            'anything' => 'else'
        ];
        $expected = Members::META;

        JsonApiAssert::assertHasMember($expected, $json);
    }

    /**
     * @test
     */
    public function assertHasMemberFailed()
    {
        $expected = 'member';
        $json = [
            'anything' => 'else'
        ];
        $failureMessage = sprintf(Messages::HAS_MEMBER, 'member');

        $this->setAssertionFailure($failureMessage);
        JsonApiAssert::assertHasMember($expected, $json);
    }

    /**
     * @test
     * @dataProvider hasMemberInvalidArgumentsProvider
     */
    public function assertHasMemberInvalidArguments($expected, $json, $arg, $type)
    {
        $this->setInvalidArgumentException($arg, $type, $arg == 1 ? $expected : $json);

        JsonApiAssert::assertHasMember($expected, $json);
    }

    public function hasMemberInvalidArgumentsProvider()
    {
        return [
            '$expected is not a string' => [
                666,
                [
                    'anything' => 'else'
                ],
                1,
                'string'
            ],
            '$json is not an array' => [
                'anything',
                'invalid',
                2,
                'array'
            ]
        ];
    }

    /**
     * @test
     */
    public function assertHasMembers()
    {
        $data = [
            Members::META => ['key' => 'value'],
            Members::JSONAPI => [
                Members::JSONAPI_VERSION => 'v1.0'
            ],
            'anything' => 'else'
        ];
        $expected = [Members::META, Members::JSONAPI];

        JsonApiAssert::assertHasMembers($expected, $data);
    }

    /**
     * @test
     */
    public function assertHasMembersFailed()
    {
        $data = [
            Members::META => ['key' => 'value'],
            'anything' => 'else'
        ];
        $keys = [Members::META, 'nothing'];
        $failureMessage = sprintf(Messages::HAS_MEMBER, 'nothing');

        $this->setAssertionFailure($failureMessage);
        JsonApiAssert::assertHasMembers($keys, $data);
    }

    /**
     * @test
     * @dataProvider hasMembersInvalidArgumentsProvider
     */
    public function assertHasMembersWithInvalidArguments($expected, $json, $arg, $type)
    {
        $this->setInvalidArgumentException($arg, $type, $arg == 1 ? $expected : $json);

        JsonApiAssert::assertHasMembers($expected, $json);
    }

    public function hasMembersInvalidArgumentsProvider()
    {
        return [
            '$expected is not an array' => [
                'invalid',
                [
                    'anything' => 'else'
                ],
                1,
                'array'
            ],
            '$json is not an array' => [
                [
                    'anything'
                ],
                'invalid',
                2,
                'array'
            ]
        ];
    }

    /**
     * @test
     */
    public function assertHasOnlyMembers()
    {
        $data = [
            Members::META => ['key' => 'value'],
            Members::JSONAPI => [
                Members::JSONAPI_VERSION => 'v1.0'
            ]
        ];
        $expected = [Members::META, Members::JSONAPI];

        JsonApiAssert::assertHasOnlyMembers($expected, $data);
    }

    /**
     * @test
     */
    public function assertHasOnlyMembersFailed()
    {
        $data = [
            Members::META => ['key' => 'value'],
            Members::JSONAPI => [
                Members::JSONAPI_VERSION => 'v1.0'
            ],
            'anything' => 'else'
        ];
        $keys = [Members::META, Members::JSONAPI];
        $failureMessage = sprintf(Messages::HAS_ONLY_MEMBERS, implode(', ', $keys));

        $this->setAssertionFailure($failureMessage);
        JsonApiAssert::assertHasOnlyMembers($keys, $data);
    }

    /**
     * @test
     * @dataProvider hasOnlyMembersInvalidArgumentsProvider
     */
    public function assertHasOnlyMembersWithInvalidArguments($expected, $json, $arg, $type)
    {
        $this->setInvalidArgumentException($arg, $type, $arg == 1 ? $expected : $json);

        JsonApiAssert::assertHasOnlyMembers($expected, $json);
    }

    public function hasOnlyMembersInvalidArgumentsProvider()
    {
        return [
            '$expected is not an array' => [
                666,
                [
                    'anything' => 'else'
                ],
                1,
                'array'
            ],
            '$json is not an array' => [
                [
                    'anything'
                ],
                'invalid',
                2,
                'array'
            ]
        ];
    }

    /**
     * @test
     */
    public function assertNotHasMember()
    {
        $data = [
            Members::META => ['key' => 'value'],
            Members::JSONAPI => [
                Members::JSONAPI_VERSION => 'v1.0'
            ]
        ];
        $expected = 'test';

        JsonApiAssert::assertNotHasMember($expected, $data);
    }

    /**
     * @test
     */
    public function assertNotHasMemberFailed()
    {
        $data = [
            'anything' => 'else'
        ];
        $expected = 'anything';
        $failureMessage = sprintf(Messages::NOT_HAS_MEMBER, $expected);

        $this->setAssertionFailure($failureMessage);
        JsonApiAssert::assertNotHasMember($expected, $data);
    }

    /**
     * @test
     * @dataProvider notHasMemberInvalidArgumentsProvider
     */
    public function assertNotHasMemberWithInvalidArguments($expected, $json, $arg, $type)
    {
        $this->setInvalidArgumentException($arg, $type, $arg == 1 ? $expected : $json);

        JsonApiAssert::assertNotHasMember($expected, $json);
    }

    public function notHasMemberInvalidArgumentsProvider()
    {
        return [
            '$expected is not a string' => [
                666,
                [
                    'anything' => 'else'
                ],
                1,
                'string'
            ],
            '$json is not an array' => [
                'anything',
                'invalid',
                2,
                'array'
            ]
        ];
    }

    /**
     * @test
     */
    public function assertNotHasMembers()
    {
        $data = [
            Members::META => ['key' => 'value'],
            Members::JSONAPI => [
                Members::JSONAPI_VERSION => 'v1.0'
            ],
        ];
        $expected = [
            'test',
            'something'
        ];

        JsonApiAssert::assertNotHasMembers($expected, $data);
    }

    /**
     * @test
     */
    public function assertNotHasMembersFailed()
    {
        $data = [
            'anything' => 'else',
            'but' => 'true'
        ];
        $expected = [
            'anything',
            'something'
        ];
        $failureMessage = sprintf(Messages::NOT_HAS_MEMBER, 'anything');

        $this->setAssertionFailure($failureMessage);
        JsonApiAssert::assertNotHasMembers($expected, $data);
    }

    /**
     * @test
     */
    public function assertNotHasMembersWithInvalidArguments()
    {
        $expected = 666;
        $json = [
            'anything' => 'else'
        ];
        $this->setInvalidArgumentException(1, 'array', $expected);

        JsonApiAssert::assertNotHasMembers($expected, $json);
    }

    /**
     * @test
     */
    public function assertHasJsonapi()
    {
        $data = [
            Members::META => ['key' => 'value'],
            Members::JSONAPI => [
                Members::JSONAPI_VERSION => 'v1.0'
            ],
        ];

        JsonApiAssert::assertHasJsonapi($data);
    }

    /**
     * @test
     */
    public function assertHasData()
    {
        $data = [
            Members::DATA => [],
            Members::JSONAPI => [
                Members::JSONAPI_VERSION => 'v1.0'
            ],
        ];

        JsonApiAssert::assertHasData($data);
    }

    /**
     * @test
     */
    public function assertHasAttributes()
    {
        $data = [
            Members::META => ['key' => 'value'],
            Members::ATTRIBUTES => [
                'attr' => 'value'
            ],
        ];

        JsonApiAssert::assertHasAttributes($data);
    }

    /**
     * @test
     */
    public function assertHasLinks()
    {
        $data = [
            Members::META => ['key' => 'value'],
            Members::LINKS => [
                Members::LINK_SELF => 'url'
            ],
        ];

        JsonApiAssert::assertHasLinks($data);
    }

    /**
     * @test
     */
    public function assertHasMeta()
    {
        $data = [
            Members::META => ['key' => 'value'],
            Members::JSONAPI => [
                Members::JSONAPI_VERSION => 'v1.0'
            ],
        ];

        JsonApiAssert::assertHasMeta($data);
    }

    /**
     * @test
     */
    public function assertHasIncluded()
    {
        $data = [
            Members::META => ['key' => 'value'],
            Members::INCLUDED => [],
        ];

        JsonApiAssert::assertHasIncluded($data);
    }

    /**
     * @test
     */
    public function assertHasRelationships()
    {
        $data = [
            Members::META => ['key' => 'value'],
            Members::RELATIONSHIPS => [],
        ];

        JsonApiAssert::assertHasRelationships($data);
    }

    /**
     * @test
     */
    public function assertHasErrors()
    {
        $data = [
            Members::META => ['key' => 'value'],
            Members::ERRORS => [],
        ];

        JsonApiAssert::assertHasErrors($data);
    }

    /**
     * @test
     */
    public function assertContainsAtLeastOneMember()
    {
        $expected = ['val1', 'val2', 'val3'];
        $data = [
            'val2' => 'valid',
            'anything' => 'else'
        ];

        JsonApiAssert::assertContainsAtLeastOneMember($expected, $data);
    }

    /**
     * @test
     */
    public function assertContainsAtLeastOneMemberFailed()
    {
        $expected = ['val1', 'val2'];
        $data = [
            'anything' => 'else',
            'something' => 'wrong'
        ];
        $failureMessage = sprintf(Messages::CONTAINS_AT_LEAST_ONE, implode(', ', $expected));

        $this->setAssertionFailure($failureMessage);
        JsonApiAssert::assertContainsAtLeastOneMember($expected, $data);
    }

    /**
     * @test
     */
    public function assertContainsOnlyAllowedMembers()
    {
        $expected = ['val1', 'val2', 'val3'];
        $data = [
            'val1' => 'valid',
            'val3' => 'and valid'
        ];

        JsonApiAssert::assertContainsOnlyAllowedMembers($expected, $data);
    }

    /**
     * @test
     */
    public function assertContainsOnlyAllowedMembersFailed()
    {
        $expected = ['val1', 'val2', 'val3'];
        $data = [
            'anything' => 'wrong',
            'val1' => 'valid'
        ];
        $failureMessage = Messages::ONLY_ALLOWED_MEMBERS;

        $this->setAssertionFailure($failureMessage);
        JsonApiAssert::assertContainsOnlyAllowedMembers($expected, $data);
    }
}
