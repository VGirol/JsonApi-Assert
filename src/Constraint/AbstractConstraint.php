<?php
declare(strict_types = 1);

namespace VGirol\JsonApiAssert\Constraint;

use PHPUnit\Framework\Constraint\Constraint;

/**
 * A constraint class to assert that a json object contains at least one member from the provided list.
 */
abstract class AbstractConstraint extends Constraint
{
    /**
     * The JsonApi-Structure constraint used to make the tests
     *
     * @var \VGirol\JsonApiStructure\Constraint\Constraint
     */
    private $constraint;

    /**
     * Returns a string representation of the constraint.
     */
    public function toString(): string
    {
        return $this->constraint->toString();
    }

    /**
     * Evaluates the constraint for parameter $other. Returns true if the
     * constraint is met, false otherwise.
     *
     * @param mixed $other value or object to evaluate
     * @return boolean
     */
    public function check($other): bool
    {
        return $this->matches($other);
    }

    /**
     * Evaluates the constraint for parameter $other. Returns true if the
     * constraint is met, false otherwise.
     *
     * @param mixed $other value or object to evaluate
     */
    protected function matches($other): bool
    {
        return $this->constraint->evaluate($other, '', true);
    }

    protected function setConstraint($constraint): void
    {
        $this->constraint = $constraint;
    }
}
