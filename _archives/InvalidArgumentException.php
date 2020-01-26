<?php
declare(strict_types = 1);

namespace VGirol\JsonApiAssert;

/**
 * Exception for invalid argument errors
 *
 * @internal
 */
class InvalidArgumentException extends \RuntimeException
{
    const MESSAGE = 'Argument #%d%s of %s::%s() must be a %s';
}
