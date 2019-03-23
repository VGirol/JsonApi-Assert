<?php
namespace VGirol\JsonApiAssert\Constraint;

use PHPUnit\Framework\Constraint\Constraint;

class ContainsAtLeastOneConstraint extends Constraint
{
    /**
     * @var array
     */
    private $members;

    public function __construct(array $members)
    {
        $this->members = $members;
    }

    /**
     * Returns a string representation of the constraint.
     */
    public function toString() : string
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
    protected function matches($other) : bool
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

    public function check($other) : bool
    {
        return $this->matches($other);
    }
}