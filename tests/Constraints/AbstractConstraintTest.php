<?php

namespace VGirol\JsonApiAssert\Tests\Constraints;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Constraint\AbstractConstraint;
use VGirol\JsonApiAssert\Tests\TestCase;
use VGirol\JsonApiStructure\Constraint\MemberName;
use VGirol\JsonApiStructure\Messages;

class AbstractConstraintTest extends TestCase
{
    protected function createCustomizedMock($strict): AbstractConstraint
    {
        return new class($strict) extends AbstractConstraint {
            public function __construct($strict)
            {
                $this->setConstraint(new MemberName($strict));
            }
        };
    }

    /**
     * @test
     */
    public function constraintMessage()
    {
        $strict = true;
        $obj = $this->createCustomizedMock($strict);

        $string = $obj->toString();
        PHPUnit::assertEquals(
            Messages::MEMBER_NAME_NOT_VALID,
            $string
        );
    }

    /**
     * @test
     */
    public function constraintCheck()
    {
        $strict = true;
        $obj = $this->createCustomizedMock($strict);

        $name = 'noProblemo';
        PHPUnit::assertTrue($obj->check($name));
    }
}
