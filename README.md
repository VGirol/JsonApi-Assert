# JsonApi-Assert

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Infection MSI][ico-mutation]][link-mutation]
[![Total Downloads][ico-downloads]][link-downloads]

This package provides a set of assertions to test documents using the [JSON:API specification](https://jsonapi.org/).

## Technologies

- PHP 7.2+
- PHPUnit 8.0+

## Installation

To install through composer, simply put the following in your `composer.json` file:

```json
{
    "require-dev": {
        "vgirol/jsonapi-assert": "dev-master"
    }
}
```

And then run `composer install` from the terminal.

### Quick Installation

Above installation can also be simplified by using the following command:

```sh
composer require vgirol/jsonapi-assert
```

## Usage

You can use these assertions in your classes directly as a static call.

```php
use VGirol\JsonApiAssert\Assert as JsonApiAssert;

class MyTest extends TestCase
{
    /**
     * @test
     */
    public function my_first_test()
    {
        $json = [
            'meta' => [
                'key' => 'value'
            ],
            'jsonapi' => [
                'version' => '1.0'
            ]
        ];

        JsonApiAssert::assertHasValidStructure($json);
    }
}
```

```php
use VGirol\JsonApiAssert\Assert as JsonApiAssert;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiAssert\SetExceptionsTrait;

class MyTest extends TestCase
{
    use SetExceptionsTrait;

    /**
     * @test
     */
    public function how_to_assert_that_a_test_failed()
    {
        $json = [
            'errors' => [
                'error' => 'not an array of error objects'
            ]
        ];
        $failureMessage = Messages::ERRORS_OBJECT_NOT_ARRAY;

        $this->setFailureException($failureMessage);

        JsonApiAssert::assertHasValidStructure($json);
    }
}
```

## Documentation

The API documentation is available in HTML format in the `./docs/api` folder.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

```sh
composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email [vincent@girol.fr](mailto:vincent@girol.fr) instead of using the issue tracker.

## Credits

- [Vincent Girol](mailto:vincent@girol.fr)
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/VGirol/JsonApi-Assert.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/VGirol/JsonApi-Assert/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/VGirol/JsonApi-Assert.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/VGirol/JsonApi-Assert.svg?style=flat-square
[ico-mutation]: https://badge.stryker-mutator.io/github.com/VGirol/JsonApi-Assert/master
[ico-downloads]: https://img.shields.io/packagist/dt/VGirol/JsonApi-Assert.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/VGirol/JsonApi-Assert
[link-travis]: https://travis-ci.org/VGirol/JsonApi-Assert
[link-scrutinizer]: https://scrutinizer-ci.com/g/VGirol/JsonApi-Assert/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/VGirol/JsonApi-Assert
[link-downloads]: https://packagist.org/packages/VGirol/JsonApi-Assert
[link-author]: https://github.com/VGirol
[link-mutation]: https://infection.github.io
[link-contributors]: ../../contributors
