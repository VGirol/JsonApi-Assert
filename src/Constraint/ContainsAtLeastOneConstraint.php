<?php
declare(strict_types = 1);

namespace VGirol\JsonApiAssert\Constraint;

use VGirol\JsonApiStructure\Constraint\ContainsAtLeastOne;

/**
 * A constraint class to assert that a json object contains at least one member from the provided list.
 */
class ContainsAtLeastOneConstraint extends AbstractConstraint
{
    /**
     * Class constructor.
     *
     * @param array<string> $members
     */
    public function __construct(array $members)
    {
        $this->setConstraint(new ContainsAtLeastOne($members));
    }
}
