<?php

declare(strict_types=1);

namespace VGirol\JsonApiAssert\Constraint;

/**
 * A constraint class to assert that a link object equals an expected value.
 */
class PaginationLinksEqualConstraint extends AbstractConstraint
{
    /**
     * Undocumented variable
     *
     * @var array
     */
    private $expected;

    /**
     * The list of the allowed link names for pagination
     *
     * @var array
     */
    private $allowedMembers;

    /**
     * Class constructor.
     *
     * @param array $expected
     * @param array $allowedMembers
     *
     * @return void
     */
    public function __construct($expected, $allowedMembers)
    {
        $this->expected = $expected;
        $this->allowedMembers = $allowedMembers;
    }

    /**
     * Returns a string representation of the constraint.
     *
     * @return string
     */
    public function toString(): string
    {
        return \sprintf(
            'equals %s',
            $this->exporter()->export($this->expected)
        );
    }

    /**
     * Evaluates the constraint for parameter $other. Returns true if the
     * constraint is met, false otherwise.
     *
     * @param mixed $other value or object to evaluate
     *
     * @return bool
     */
    protected function matches($other): bool
    {
        // Add missing members with false value
        $cleanExpected = array_merge(
            array_fill_keys($this->allowedMembers, false),
            $this->expected
        );
        asort($cleanExpected);

        // Extract only pagination members from incoming json
        $cleanJson = array_intersect_key($other, array_flip($this->allowedMembers));
        asort($cleanJson);

        // Search for unexpected members
        $notExpectedMembers = array_keys(
            array_filter(
                $cleanExpected,
                /**
                 * @param mixed $value
                 *
                 * @return bool
                 */
                function ($value) {
                    return $value === false;
                }
            )
        );
        if (count(array_intersect_key($cleanJson, array_flip($notExpectedMembers))) !== 0) {
            return false;
        }

        // Extracts expected members
        $expectedMembers = array_filter(
            $cleanExpected,
            /**
             * @param mixed $value
             *
             * @return bool
             */
            function ($value) {
                return $value !== false;
            }
        );
        if (array_keys($expectedMembers) != array_keys($cleanJson)) {
            return false;
        }

        // Extracts members whose value have to be tested
        $expectedValues = array_filter(
            $expectedMembers,
            /**
             * @param mixed $value
             *
             * @return bool
             */
            function ($value) {
                return $value !== true;
            }
        );

        foreach ($expectedValues as $name => $expectedLink) {
            $constraint = new LinkEqualsConstraint($expectedLink);
            if ($constraint->check($cleanJson[$name]) === false) {
                return false;
            }
        }

        return true;
    }
}
