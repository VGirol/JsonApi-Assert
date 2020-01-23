<?php

declare(strict_types=1);

namespace VGirol\JsonApiAssert;

use PHPUnit\Framework\ExpectationFailedException;
use VGirol\JsonApiStructure\Testing\SetExceptionsTrait as TestingSetExceptionsTrait;

/**
 * Some helpers for testing
 *
 * @codeCoverageIgnore
 */
trait SetExceptionsTrait
{
    use TestingSetExceptionsTrait {
        setFailure as parentSetFailure;
    }

    protected function setFailure(?string $message = null, $code = null): void
    {
        $this->parentSetFailure($message, $code, ExpectationFailedException::class);
    }
}
