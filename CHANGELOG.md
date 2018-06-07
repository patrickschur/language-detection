# Change Log
All notable changes to this project will be documented in this file.

## [3.3.0] - 2018-06-07
### Added
- Serbian cyrylic and serbian latin language files

### Changed
- The method `__toString` returns always a string

## [3.2.0] - 2017-08-20
### Added
- Experimental support for custom directories. You can now specify your own path for translation files.
- Credits

### Changed
- Corrections to Amharic (`am`) translation
- Corrections to Czech (`cs`) translation
- Corrections to Lao (`lo`) translation

## [3.1.1] - 2017-07-27
### Changed
- Return empty object if no result was found

## [3.1.0] - 2017-03-14
### Added
- Support for the languages Lingala, Sanskrit, Tonga and Urdu

## [3.0.0] - 2017-02-05
### Added
- `CHANGELOG.md`
- `CONTRIBUTING.md`
- Tokenizer interface

### Changed
- Folder structure (renamed `etc` to `resources`)
- Renamed all language files (added **.txt** extension)
- Improved performance
- Updated to PHPUnit 6
- `README.md`, thanks to [stof](https://github.com/stof)

### Removed
- Autoloader script
- Language model `_langs.json`

## [2.1.1] - 2017-02-01
### Added
- Autloader script

### Changed
- Fixed typos for Lithuanian language sample, thanks to [tomasliubinas](https://github.com/tomasliubinas)
- Fixed wrong ISO 639-1 codes
- Updated `_langs.json`

### Removed
- `php-nightly` in `.travis.yml`

## [2.1.0] - 2017-01-27
### Added
- New best results method
- Some PHPUnit tests

### Changed
- Fixed a bug that could produce slightly worse results.
- `README.md`
- `LICENSE.md`

## [2.0.0] - 2017-01-09
### Added
- `ta` language file
- IteratorAggregate interface

### Changed
- Updated `_langs.json`
- `README.md`

## [1.2.0] - 2017-01-04
### Added
- `ay` language file
- `bi` language file
- `bo` language file
- `br` language file
- `bs-Cyrl` language file
- `bs-Latn` language file
- `ca` language file
- `ch` language file
- `dz` language file
- `fy` language file
- `gu` language file
- `id` language file
- `jv` language file
- `km` language file
- `kr` language file
- `ms-Latn` language file
- `ng` language file
- `pt-BR` language file
- `sq` language file
- `ss` language file

### Changed
- Changed folder structure
- `ms-Arab` renamed from `ms`
- `pt-PT` renamed from `pt`
- `README.md`
- Updated `_langs.json`

## [1.1.0] - 2016-12-31
### Added
- `tl` language file
- `tt` language file
- `ve` language file
- `wa` language file
- `wo` language file
- `xh` language file
- `yo` language file
- `ug-Latn` language file

### Changed
- `_langs.json`
- `ug-Arab` renamed from `ug`
- `README.md`

## [1.0.0] - 2016-12-25
- First Release
