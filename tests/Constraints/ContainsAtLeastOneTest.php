<?php

namespace VGirol\JsonApiAssert\Tests\Constraints;

use VGirol\JsonApiAssert\Constraint\ContainsAtLeastOneConstraint;
use VGirol\JsonApiAssert\Tests\TestCase;

class ContainsAtLeastOneTest extends TestCase
{
    /**
     * @test
     */
    public function assertContainsAtLeastOne()
    {
        $allowed = ['anything', 'something'];

        $constraint = new ContainsAtLeastOneConstraint($allowed);

        $json = [
            'anything' => 'ok',
            'another' => 'ok'
        ];
        $this->assertTrue($constraint->evaluate($json, '', true));
    }

    /**
     * @test
     * @dataProvider assertContainsAtLeastOneFailedProvider
     */
    public function assertContainsAtLeastOneFailed($json)
    {
        $allowed = ['anything', 'something'];

        $constraint = new ContainsAtLeastOneConstraint($allowed);

        $this->assertFalse($constraint->evaluate($json, '', true));
    }

    public function assertContainsAtLeastOneFailedProvider()
    {
        return [
            'not an array' => [
                'failed'
            ],
            'no one expected member' => [
                [
                    'unexpected' => 'bad'
                ]
            ]
        ];
    }

    /**
     * @test
     */
    public function assertContainsAtLeastOneFailedAndThrowException()
    {
        $allowed = ['anything', 'something'];
        $json = [
            'unexpected' => 'bad'
        ];

        $constraint = new ContainsAtLeastOneConstraint($allowed);

        $this->setFailure(sprintf(
            '/Failed asserting that [\S\s]* %s\./',
            $constraint->toString()
        ));

        $constraint->evaluate($json);
    }
}
