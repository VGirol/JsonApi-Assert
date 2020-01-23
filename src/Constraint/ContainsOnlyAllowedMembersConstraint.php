<?php
declare(strict_types = 1);

namespace VGirol\JsonApiAssert\Constraint;

use VGirol\JsonApiStructure\Constraint\ContainsOnlyAllowedMembers;

/**
 * A constraint class to assert that a json object contains only members from the provided list.
 */
class ContainsOnlyAllowedMembersConstraint extends AbstractConstraint
{
    /**
     * Class constructor.
     *
     * @param array<string> $members
     */
    public function __construct(array $members)
    {
        $this->setConstraint(new ContainsOnlyAllowedMembers($members));
    }
}
