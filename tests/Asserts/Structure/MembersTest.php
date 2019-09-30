<?php
namespace VGirol\JsonApiAssert\Tests\Asserts\Structure;

use VGirol\JsonApiAssert\Assert as JsonApiAssert;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiAssert\Tests\TestCase;

class MembersTest extends TestCase
{
    /**
     * @test
     */
    public function assertHasMember()
    {
        $json = [
            'data' => 'jsonapi',
            'meta' => 'valid'
        ];
        $expected = 'data';

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

        $this->setFailure($failureMessage);
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
            'data' => 'jsonapi',
            'meta' => 'valid',
            'jsonapi' => 'ok'
        ];
        $expected = ['data', 'meta'];

        JsonApiAssert::assertHasMembers($expected, $data);
    }

    /**
     * @test
     */
    public function assertHasMembersFailed()
    {
        $data = [
            'meta' => 'test',
            'anything' => 'else'
        ];
        $keys = ['meta', 'nothing'];
        $failureMessage = sprintf(Messages::HAS_MEMBER, 'nothing');

        $this->setFailure($failureMessage);
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
            'data' => 'jsonapi',
            'meta' => 'valid'
        ];
        $expected = ['data', 'meta'];

        JsonApiAssert::assertHasOnlyMembers($expected, $data);
    }

    /**
     * @test
     */
    public function assertHasOnlyMembersFailed()
    {
        $data = [
            'data' => 'jsonapi',
            'meta' => 'test',
            'anything' => 'else'
        ];
        $keys = ['meta', 'data'];
        $failureMessage = sprintf(Messages::HAS_ONLY_MEMBERS, implode(', ', $keys));

        $this->setFailure($failureMessage);
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
            'data' => 'jsonapi',
            'meta' => 'valid'
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

        $this->setFailure($failureMessage);
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
            'data' => 'jsonapi',
            'meta' => 'valid'
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
            'meta' => 'test'
        ];
        $expected = [
            'anything',
            'something'
        ];
        $failureMessage = sprintf(Messages::NOT_HAS_MEMBER, 'anything');

        $this->setFailure($failureMessage);
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
            'jsonapi' => 'valid',
            'another' => 'member'
        ];

        JsonApiAssert::assertHasJsonapi($data);
    }

    /**
     * @test
     */
    public function assertHasData()
    {
        $data = [
            'meta' => 'valid',
            'data' => 'jsonapi'
        ];

        JsonApiAssert::assertHasData($data);
    }

    /**
     * @test
     */
    public function assertHasAttributes()
    {
        $data = [
            'meta' => 'valid',
            'attributes' => 'jsonapi'
        ];

        JsonApiAssert::assertHasAttributes($data);
    }

    /**
     * @test
     */
    public function assertHasLinks()
    {
        $data = [
            'meta' => 'valid',
            'links' => 'jsonapi'
        ];

        JsonApiAssert::assertHasLinks($data);
    }

    /**
     * @test
     */
    public function assertHasMeta()
    {
        $data = [
            'meta' => 'valid',
            'links' => 'jsonapi'
        ];

        JsonApiAssert::assertHasMeta($data);
    }

    /**
     * @test
     */
    public function assertHasIncluded()
    {
        $data = [
            'meta' => 'valid',
            'included' => 'jsonapi'
        ];

        JsonApiAssert::assertHasIncluded($data);
    }

    /**
     * @test
     */
    public function assertHasRelationships()
    {
        $data = [
            'meta' => 'valid',
            'relationships' => 'jsonapi'
        ];

        JsonApiAssert::assertHasRelationships($data);
    }

    /**
     * @test
     */
    public function assertHasErrors()
    {
        $data = [
            'meta' => 'valid',
            'errors' => 'jsonapi'
        ];

        JsonApiAssert::assertHasErrors($data);
    }

    /**
     * @test
     */
    public function assertContainsAtLeastOneMember()
    {
        $expected = ['first', 'second', 'meta'];
        $data = [
            'meta' => 'valid',
            'errors' => 'jsonapi'
        ];

        JsonApiAssert::assertContainsAtLeastOneMember($expected, $data);
    }

    /**
     * @test
     */
    public function assertContainsAtLeastOneMemberFailed()
    {
        $expected = ['first', 'second'];
        $data = [
            'meta' => 'valid',
            'errors' => 'jsonapi'
        ];
        $failureMessage = sprintf(Messages::CONTAINS_AT_LEAST_ONE, implode(', ', $expected));

        $this->setFailure($failureMessage);
        JsonApiAssert::assertContainsAtLeastOneMember($expected, $data);
    }

    /**
     * @test
     */
    public function assertContainsOnlyAllowedMembers()
    {
        $expected = ['first', 'second', 'meta'];
        $data = [
            'meta' => 'valid',
            'first' => 'jsonapi'
        ];

        JsonApiAssert::assertContainsOnlyAllowedMembers($expected, $data);
    }

    /**
     * @test
     */
    public function assertContainsOnlyAllowedMembersFailed()
    {
        $expected = ['first', 'second', 'meta'];
        $data = [
            'meta' => 'valid',
            'errors' => 'jsonapi'
        ];
        $failureMessage = Messages::ONLY_ALLOWED_MEMBERS;

        $this->setFailure($failureMessage);
        JsonApiAssert::assertContainsOnlyAllowedMembers($expected, $data);
    }
}
