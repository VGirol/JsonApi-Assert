<?php

namespace VGirol\JsonApiAssert\Asserts\Content;

use DMS\PHPUnitExtensions\ArraySubset\Assert as AssertArray;
use DMS\PHPUnitExtensions\ArraySubset\Constraint\ArraySubset;
use PHPUnit\Framework\Assert as PHPUnit;
use PHPUnit\Framework\ExpectationFailedException;
use VGirol\JsonApiAssert\Messages;

/**
 * This trait adds the ability to test errors content.
 */
trait AssertErrors
{
    /**
     * Asserts that an errors array contains a given subset of expected errors.
     *
     * It will do the following checks :
     * 1) asserts that the errors array is valid (@see assertIsValidErrorsObject).
     * 2) asserts that the errors array length is greater or equal than the expected errors array length.
     * 3) asserts that each expected error is present in the errors array.
     *
     * @link https://jsonapi.org/format/#error-objects
     *
     * @param array   $expected An array of expected error objects
     * @param array   $errors   An array of errors to inspect
     * @param boolean $strict   If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertErrorsContains($expected, $errors, $strict)
    {
        try {
            static::assertIsValidErrorsObject($expected, $strict);
        } catch (ExpectationFailedException $e) {
            static::invalidArgument(1, 'errors object', $expected);
        }

        static::assertIsValidErrorsObject($errors, $strict);

        PHPUnit::assertGreaterThanOrEqual(
            count($expected),
            count($errors),
            Messages::ERRORS_OBJECT_CONTAINS_NOT_ENOUGH_ERRORS
        );

        foreach ($expected as $expectedError) {
            $test = false;
            foreach ($errors as $error) {
                $constraint = new ArraySubset($expectedError, true);
                if ($constraint->evaluate($error, '', true)) {
                    $test = true;
                    break;
                }
            }
            PHPUnit::assertTrue($test);
            // PHPUnit::assertContains($expectedError, $errors);
        }
    }
}
