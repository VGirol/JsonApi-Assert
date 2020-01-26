<?php

declare(strict_types=1);

namespace VGirol\JsonApiAssert;

use PHPUnit\Framework\AssertionFailedError;
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

    /**
     * Undocumented function
     *
     * @param string|null $message
     * @param int|null    $code
     *
     * @return void
     */
    protected function setFailure(?string $message = null, $code = null): void
    {
        $this->parentSetFailure($message, $code, AssertionFailedError::class);
    }
}
