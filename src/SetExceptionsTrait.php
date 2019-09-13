<?php

declare(strict_types=1);

namespace VGirol\JsonApiAssert;

use PHPUnit\Framework\ExpectationFailedException;

/**
 * Some helpers for testing
 *
 * @codeCoverageIgnore
 */
trait SetExceptionsTrait
{
    /**
     * Set the class name of the expected exception
     *
     * @see \PHPUnit\Framework\TestCase::expectException
     *
     * @param string $exception
     *
     * @return void
     */
    abstract public function expectException(string $exception): void;

    /**
     * Set the message of the expected exception
     *
     * @see \PHPUnit\Framework\TestCase::expectExceptionMessage
     *
     * @param string $message
     *
     * @return void
     */
    abstract public function expectExceptionMessage(string $message): void;

    /**
     * Set the a regular expression for the message of the expected exception
     *
     * @see \PHPUnit\Framework\TestCase::expectExceptionMessageRegExp
     *
     * @param string $messageRegExp
     *
     * @return void
     */
    abstract public function expectExceptionMessageRegExp(string $messageRegExp): void;

    /**
     * Set the expected exception and message when defining a test that will fail.
     *
     * @param string|null $message
     *
     * @return void
     */
    protected function setFailureException($message = null)
    {
        $this->expectException(ExpectationFailedException::class);
        if ($message !== null) {
            $this->expectExceptionMessage($message);
        }
    }

    /**
     * Set the expected exception and message when defining a test that will fail.
     *
     * @param string|null $message
     *
     * @return void
     */
    protected function setFailureExceptionRegex($message = null)
    {
        $this->expectException(ExpectationFailedException::class);
        if ($message !== null) {
            $this->expectExceptionMessageRegExp($message);
        }
    }

    /**
     * Set the expected exception and message when testing a call with invalid arguments to a method.
     *
     * @param integer $arg
     * @param string  $type
     * @param mixed   $value
     *
     * @return void
     */
    protected function setInvalidArgumentException(int $arg, string $type, $value = null)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageRegExp(
            \sprintf(
                '/' . \preg_quote(InvalidArgumentException::MESSAGE) . '/',
                $arg,
                ($value === null) ?
                    '[\s\S]*' : ' \(' . \gettype($value) . '#' . \preg_quote(\var_export($value, true)) . '\)',
                '.*',
                '.*',
                \preg_quote($type)
            )
        );
    }
}
