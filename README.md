# Concise CLI

[![Latest Version](https://img.shields.io/github/release/toin0u/concise-cli.svg?style=flat-square)](https://github.com/toin0u/concise-cli/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build Status](https://img.shields.io/travis/toin0u/concise-cli.svg?style=flat-square)](https://travis-ci.org/toin0u/concise-cli)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/toin0u/concise-cli.svg?style=flat-square)](https://scrutinizer-ci.com/g/toin0u/concise-cli)
[![Quality Score](https://img.shields.io/scrutinizer/g/toin0u/concise-cli.svg?style=flat-square)](https://scrutinizer-ci.com/g/toin0u/concise-cli)
[![HHVM Status](https://img.shields.io/hhvm/toin0u/concise-cli.svg?style=flat-square)](http://hhvm.h4cc.de/package/toin0u/concise-cli)
[![Total Downloads](https://img.shields.io/packagist/dt/toin0u/concise-cli.svg?style=flat-square)](https://packagist.org/packages/toin0u/concise-cli)


**Concise your urls from console.**


## Install

Via Composer

``` bash
$ composer require toin0u/concise-cli
```


## Usage

Create a configuration file in one of the following locations:

- `$HOME/.concise.yml`
- `./concise.yml`
- `./.concise.yml`
- any other location, just make sure to pass it with the -c/--config switch: `concise.phar <command> -c path/to/config.yml`

Paste something similar to [this](resources/concise.yml.example) in the file (schema can be found [here](resources/schema.yml)).

Run one of the following commands:

- `concise.phar shorten http://google.hu`
- `concise.phar expand http://goo.gl/hash`
- `concise.phar validate`


## Testing

``` bash
$ phpunit
```


## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.


## Credits

- [Antoine Corcy](https://github.com/toin0u)
- [Márk Sági-Kazár](https://github.com/sagikazarmark)
- [All Contributors](https://github.com/toin0u/concise-cli/contributors)


## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
