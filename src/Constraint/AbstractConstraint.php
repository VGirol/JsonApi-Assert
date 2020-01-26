<?php
declare(strict_types = 1);

namespace VGirol\JsonApiAssert\Constraint;

use PHPUnit\Framework\Constraint\Constraint;

/**
 * Abstract constraint class
 */
abstract class AbstractConstraint extends Constraint implements ConstraintContract
{
    /**
     * The JsonApi-Structure constraint used to make the tests
     *
     * @var \VGirol\JsonApiStructure\Constraint\Constraint
     */
    private $constraint;

    /**
     * Returns a string representation of the constraint.
     *
     * @return string
     */
    public function toString(): string
    {
        return $this->constraint->toString();
    }

    /**
     * Evaluates the constraint for parameter $other. Returns true if the constraint is met, false otherwise.
     *
     * @param mixed $other value or object to evaluate
     *
     * @return boolean
     */
    public function check($other): bool
    {
        return $this->matches($other);
    }

    /**
     * Evaluates the constraint for parameter $other. Returns true if the constraint is met, false otherwise.
     *
     * @param mixed $other value or object to evaluate
     *
     * @return bool
     */
    protected function matches($other): bool
    {
        return $this->constraint->handle($other);
    }

    /**
     * Undocumented function
     *
     * @param \VGirol\JsonApiStructure\Constraint\Constraint $constraint
     *
     * @return void
     */
    protected function setConstraint($constraint): void
    {
        $this->constraint = $constraint;
    }
}
