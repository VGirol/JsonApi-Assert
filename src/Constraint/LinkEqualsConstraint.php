<?php

declare(strict_types=1);

namespace VGirol\JsonApiAssert\Constraint;

use PHPUnit\Framework\Constraint\Constraint;
use VGirol\JsonApiAssert\Members;

/**
 * A constraint class to assert that a link object equals an expected value.
 */
class LinkEqualsConstraint extends Constraint
{
    /**
     * @var array|string|null
     */
    private $expected;

    /**
     * Class constructor.
     *
     * @param array|string|null $expected
     */
    public function __construct($expected)
    {
        $this->expected = $expected;
    }

    /**
     * Returns a string representation of the constraint.
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
     * @param array|string|null $other value or object to evaluate
     */
    protected function matches($other): bool
    {
        if ($this->expected === null) {
            return ($other === null);
        }

        if ($other === null) {
            return false;
        }

        /** @var string $href */
        $href = \is_array($other) && isset($other[Members::LINK_HREF]) ? $other[Members::LINK_HREF] : $other;

        /** @var string $expectedHref */
        $expectedHref = \is_array($this->expected) && isset($this->expected[Members::LINK_HREF]) ?
            $this->expected[Members::LINK_HREF] : $this->expected;

        $linkElms = explode('?', $href);
        $expectedElms = explode('?', $expectedHref);

        if (count($expectedElms) != count($linkElms)) {
            return false;
        }

        if ($expectedElms[0] != $linkElms[0]) {
            return false;
        }

        if (count($linkElms) == 1) {
            return true;
        }

        $expectedQuery = explode('&', $expectedElms[1]);
        $linkQuery = explode('&', $linkElms[1]);

        if (count($expectedQuery) != count($linkQuery)) {
            return false;
        }

        $diff = array_diff($expectedQuery, $linkQuery);

        return count($diff) === 0;
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
