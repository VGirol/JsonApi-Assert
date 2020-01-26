<?php
declare(strict_types = 1);

namespace VGirol\JsonApiAssert\Constraint;

/**
 * Contract for all constraint classes
 */
interface ConstraintContract
{
    /**
     * Evaluates the constraint for parameter $other. Returns true if the constraint is met, false otherwise.
     *
     * @param mixed $other value or object to evaluate
     *
     * @return boolean
     */
    public function check($other): bool;
}
