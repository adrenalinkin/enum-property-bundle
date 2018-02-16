## [2.0.0] - 2018-02-16
## Added
- Added function to the twig extension `enum_allowed_db`, `enum_allowed_human`, `enum_map`,
    `enum_random_db`, `enum_random_human`.
- Added local cache to the twig extension.
- Added informative PhpDoc.
- Improved documentation.
- Added english version of the documentation.
### Changed
- Renamed twig filters to `enum_to_human` and `enum_to_db`.
### Removed
- Removed translation features.
- Removed mapper service.

## [1.1.0] - 2017-12-06
### Added
- Added support of the Symfony `2.8`.
- Added support of the Twig `2`.
### Removed
- Removed support of the Symfony lower than `2.8` version.
### Changed
- Minimal PHP version upped to `5.6`.
- Minimal Twig version upped to `1.35`.

## [1.0.3] - 2017-11-30
### Changed
- Approved support of the Symfony `2.7`.

## [1.0.2] - 2017-11-22
### Changed
- Approved support of the Symfony `2.6`.

## [1.0.1] - 2017-11-15
### Fixed
- Applied Symfony Standards over the PSR-2

## [1.0.0]
### Added
- Basic feature which provides possibility to integrate [EnumMapper](https://github.com/adrenalinkin/enum-mapper)
    component into symfony project.
- Support translations usage.
- Support validation usage.
- Support integration with TWIG.
- Support DI service usage.
