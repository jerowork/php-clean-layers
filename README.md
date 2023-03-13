# php-clean-layers
[![Build Status](https://img.shields.io/endpoint.svg?url=https%3A%2F%2Factions-badge.atrox.dev%2Fjerowork%2Fphp-clean-layers%2Fbadge%3Fref%3Dmain&style=flat-square)](https://github.com/jerowork/php-clean-layers/actions)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/jerowork/php-clean-layers.svg?style=flat-square)](https://scrutinizer-ci.com/g/jerowork/php-clean-layers/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/jerowork/php-clean-layers.svg?style=flat-square)](https://scrutinizer-ci.com/g/jerowork/php-clean-layers)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Packagist Version](https://img.shields.io/packagist/v/jerowork/php-clean-layers.svg?style=flat-square&include_prereleases)](https://packagist.org/packages/jerowork/php-clean-layers)
[![PHP Version](https://img.shields.io/badge/php-%5E8.1-8892BF.svg?style=flat-square)](http://www.php.net)

Guard your architectural layers with static analysis.

The word 'clean' is a 😉 to Uncle Bob's [Clean Architecture](https://blog.cleancoder.com/uncle-bob/2012/08/13/the-clean-architecture.html) (also known as Hexagonal Architecture).

## Installation
Install via Composer:
```bash
composer require --dev jerowork/php-clean-layers
```

## Configuration
Copy the necessary configuration files in your root directory with:
```bash
vendor/bin/phpcl init
```

It will copy a configuration yaml file [`phpcl.yaml`](resources/templates/phpcl.yaml) and a template Guard test class [`CleanArchitectureGuard.php`](resources/templates/CleanArchitectureGuard.php).

### The configuration file
Default `phpcl.yaml`:
```yaml
parameters:
  path:
    source: ./src
    guards:
      - CleanArchitectureGuard.php
  baseline: ./phpcl-baseline.yaml
```

Configuration options:

| Option         | Description                                                                        | Format                     |
|----------------|------------------------------------------------------------------------------------|----------------------------|
| `path.source`  | Path to your src directory                                                         | `string`                   | 
| `path.guards`  | A set of paths to your Guard test classes (directories and/or direct files paths)  | `list<string>` or `string` |
| `baseline`     | Baseline filename                                                                  | `string`                   |

### The Guard test class 
A Guard test class consists of one or more test cases, registered with the `#[Test]` Attributes.
Each test case returns a `Guard` with one or more `Rules`:

```php
use Jerowork\PHPCleanLayers\Attribute\Test;
use Jerowork\PHPCleanLayers\Guard\Guard;
use Jerowork\PHPCleanLayers\Guard\Layer\RegexLayer;
use Jerowork\PHPCleanLayers\Guard\Layer\RootLevelClasses;
use Jerowork\PHPCleanLayers\Guard\Rule\OnlyDependOn;
use Jerowork\PHPCleanLayers\Guard\Rule\NotBeAllowedBy;

final class SomeGuard
{
    #[Test]
    public function guardThatLayerOnlyDependsOnX(): Guard
    {
        return Guard::layer('Some\Layer')
            ->should(new OnlyDependOn(
                new RootLevelClasses(),
                'Depend\On\Layer',
                RegexLayer::create('Depend\On\Another\Layer')
                    ->excluding('SubLayer1', 'SubLayer2'),
            ))
            ->should(new NotBeAllowedBy('Not\Allowed\Layer'));
    }
}
```

Available Rules:

| Rule              | Description                                                  |
|-------------------|--------------------------------------------------------------|
| `NotDependOn`     | Define which layers the guarded layer should not depend on.  |
| `OnlyDependOn`    | Define which layers the guarded layer should only depend on. |
| `NoBeAllowedBy`   | Define which layers cannot use the guarded layer.            |
| `OnlyBeAllowedBy` | Define which layers can only use the guarded layer.          |

All layer input argument are by default of format `string`. For more complex layer definitions, use the `RegexLayer`.

The predefined layer `RootLevelClasses` can be used to for native PHP functions, classes, etc.

## Usage
Run the following:
```bash
vendor/bin/phpcl guard [--config=./phpcl.yaml] [--generate-baseline]
```
