<?php

namespace VGirol\JsonApiAssert\Tests\Constraints;

use VGirol\JsonApiAssert\Constraint\ContainsOnlyAllowedMembersConstraint;
use VGirol\JsonApiAssert\Tests\TestCase;

class ContainsOnlyAllowedMembersTest extends TestCase
{
    /**
     * @test
     */
    public function assertContainsOnlyAllowedMembers()
    {
        $allowed = ['anything', 'something'];

        $constraint = new ContainsOnlyAllowedMembersConstraint($allowed);

        $json = [
            'anything' => 'ok'
        ];
        $this->assertTrue($constraint->evaluate($json, '', true));
    }

    /**
     * @test
     * @dataProvider assertContainsOnlyAllowedMembersFailedProvider
     */
    public function assertContainsOnlyAllowedMembersFailed($json)
    {
        $allowed = ['anything', 'something'];

        $constraint = new ContainsOnlyAllowedMembersConstraint($allowed);

        $this->assertFalse($constraint->evaluate($json, '', true));
    }

    public function assertContainsOnlyAllowedMembersFailedProvider()
    {
        return [
            'not an array' => [
                'failed'
            ],
            'not only allowed members' => [
                [
                    'anything' => 'ok',
                    'notAllowed' => 'bad'
                ]
            ]
        ];
    }

    /**
     * @test
     */
    public function assertContainsOnlyAllowedMembersThrowsException()
    {
        $allowed = ['anything', 'something'];
        $json = [
            'anything' => 'ok',
            'notAllowed' => 'bad'
        ];

        $constraint = new ContainsOnlyAllowedMembersConstraint($allowed);

        $this->setFailure(
            sprintf(
                '/Failed asserting that [\S\s]* %s\./',
                $constraint->toString()
            )
        );

        $constraint->evaluate($json);
    }
}
