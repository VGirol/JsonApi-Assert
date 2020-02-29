<?php

namespace VGirol\JsonApiAssert\Tests;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\TestCase as BaseTestCase;
use VGirol\PhpunitException\SetExceptionsTrait;

abstract class TestCase extends BaseTestCase
{
    use SetExceptionsTrait;

    public function setAssertionFailure(?string $message = null, $code = null): void
    {
        $this->setFailure(AssertionFailedError::class, $message, $code);
    }
}
