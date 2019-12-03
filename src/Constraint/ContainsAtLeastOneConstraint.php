<?php
declare(strict_types = 1);

namespace VGirol\JsonApiAssert\Constraint;

use PHPUnit\Framework\Constraint\Constraint;

/**
 * A constraint class to assert that a json object contains at least one member from the provided list.
 */
class ContainsAtLeastOneConstraint extends Constraint
{
    /**
     * @var array<string>
     */
    private $members;

    /**
     * Class constructor.
     *
     * @param array<string> $members
     */
    public function __construct(array $members)
    {
        $this->members = $members;
    }

    /**
     * Returns a string representation of the constraint.
     */
    public function toString(): string
    {
        return \sprintf(
            'contains at least one element of "%s"',
            \implode(', ', $this->members)
        );
    }

    /**
     * Evaluates the constraint for parameter $other. Returns true if the
     * constraint is met, false otherwise.
     *
     * @param mixed $other value or object to evaluate
     */
    protected function matches($other): bool
    {
        if (!is_array($other)) {
            return false;
        }

        foreach ($this->members as $member) {
            if (array_key_exists($member, $other)) {
                return true;
            }
        }

        return false;
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
}
