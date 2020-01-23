<?php
declare(strict_types = 1);

namespace VGirol\JsonApiAssert\Constraint;

use VGirol\JsonApiStructure\Constraint\MemberName;

/**
 * A constraint class to assert that a json object contains at least one member from the provided list.
 */
class MemberNameConstraint extends AbstractConstraint
{
    /**
     * Class constructor.
     *
     * @param bool $strict
     */
    public function __construct(bool $strict)
    {
        $this->setConstraint(new MemberName($strict));
    }
}
