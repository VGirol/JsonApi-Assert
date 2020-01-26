<?php

declare(strict_types=1);

namespace VGirol\JsonApiAssert;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiStructure\Exception\ValidationException;
use VGirol\JsonApiStructure\ValidateService;

/**
 * This trait add the ability to call ValidateService.
 */
trait HaveValidationService
{
    /**
     * Undocumented variable
     *
     * @var ValidateService
     */
    private static $service;

    /**
     * Undocumented function
     *
     * @return ValidateService
     */
    protected static function getServiceInstance(): ValidateService
    {
        if (self::$service === null) {
            self::$service = static::createServiceInstance();
        }

        return self::$service;
    }

    /**
     * Undocumented function
     *
     * @param string $method
     * @param mixed ...$args
     *
     * @return mixed
     */
    protected static function proxyService(string $method, ...$args)
    {
        $service = static::getServiceInstance();

        return \call_user_func([$service, $method], ...$args);
    }

    /**
     * Undocumented function
     *
     * @param string $method
     * @param mixed ...$args
     *
     * @return void
     */
    protected static function askService(string $method, ...$args): void
    {
        try {
            static::proxyService($method, ...$args);
            static::succeed();
        } catch (ValidationException $e) {
            PHPUnit::fail($e->getMessage());
        }
    }

    /**
     * Undocumented function
     *
     * @param string $message
     *
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    protected static function succeed(string $message = ''): void
    {
        PHPUnit::assertTrue(true, $message);
    }

    /**
     * Undocumented function
     *
     * @return ValidateService
     */
    private static function createServiceInstance(): ValidateService
    {
        return new ValidateService();
    }
}
