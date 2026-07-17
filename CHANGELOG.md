# Changelog

All notable changes to `barstool` will be documented in this file.

## v1.2.0 - 2026-07-17

### What's Changed

* Add `Barstool::context()` for storing custom context on recordings by @craigpotter in https://github.com/saloonphp/barstool/pull/13
* Add PHP 8.5 support by @craigpotter in https://github.com/saloonphp/barstool/pull/14
* Add `only` allowlist for connectors and requests by @craigpotter in https://github.com/saloonphp/barstool/pull/15
* Overhaul README configuration docs by @craigpotter in https://github.com/saloonphp/barstool/pull/16

**Full Changelog**: https://github.com/saloonphp/barstool/compare/v1.1.2...v1.2.0

## v1.1.2 - 2026-07-14

### What's Changed

* Fix streamed response bodies being consumed when recording by @craigpotter in https://github.com/saloonphp/barstool/pull/11
* Fix race condition where response recording can run before request recording under concurrent workers by @lloricode in https://github.com/saloonphp/barstool/pull/10
* Bump actions/checkout from 6 to 7 by @dependabot[bot] in https://github.com/saloonphp/barstool/pull/9

**Full Changelog**: https://github.com/saloonphp/barstool/compare/v1.1.1...v1.1.2

## v1.1.1 - 2026-04-27

### What's Changed

* Fix | Remove old unused status property by @lloricode in https://github.com/saloonphp/barstool/pull/2
* Fix migration using wrong config key for database connection by @craigpotter in https://github.com/saloonphp/barstool/pull/3
* Bump dependabot/fetch-metadata from 2.5.0 to 3.1.0 by @dependabot[bot] in https://github.com/saloonphp/barstool/pull/5

### New Contributors

* @lloricode made their first contribution in https://github.com/saloonphp/barstool/pull/2
* @craigpotter made their first contribution in https://github.com/saloonphp/barstool/pull/3
* @dependabot[bot] made their first contribution in https://github.com/saloonphp/barstool/pull/5

**Full Changelog**: https://github.com/saloonphp/barstool/compare/v1.1.0...v1.1.1

## v1.1.0 - 2026-03-31

### What's Changed

* Add queue support for Barstool recordings by @niladam in https://github.com/saloonphp/barstool/pull/1

### New Contributors

* @niladam made their first contribution in https://github.com/saloonphp/barstool/pull/1

**Full Changelog**: https://github.com/saloonphp/barstool/compare/v1.0.0...v1.1.0

## v1.0.0 - 2026-03-26

**Full Changelog**: https://github.com/saloonphp/barstool/commits/v1.0.0

Migrated from https://github.com/craigpotter/barstool

## v0.6.1 - 2025-07-29

### What's Changed

* Bump stefanzweifel/git-auto-commit-action from 5 to 6 by @dependabot[bot] in https://github.com/saloonphp/barstool/pull/22

**Full Changelog**: https://github.com/saloonphp/barstool/compare/v0.6.0...v0.6.1

## v0.6.0 - 2025-05-21

### What's Changed

* Bump dependabot/fetch-metadata from 2.3.0 to 2.4.0 by @dependabot in https://github.com/saloonphp/barstool/pull/20
* Feature | Support application/soap+xml content type by @Sammyjo20 in https://github.com/saloonphp/barstool/pull/21

### New Contributors

* @Sammyjo20 made their first contribution in https://github.com/saloonphp/barstool/pull/21

**Full Changelog**: https://github.com/saloonphp/barstool/compare/v0.5.0...v0.6.0

## Version v0.5.0 - 2025-02-26

### What's Changed

* Exclude authorization header by default by @JonPurvis in https://github.com/saloonphp/barstool/pull/19
* account for a lowercase content-type header by @JonPurvis in https://github.com/saloonphp/barstool/pull/18

### New Contributors

* @JonPurvis made their first contribution in https://github.com/saloonphp/barstool/pull/19

**Full Changelog**: https://github.com/saloonphp/barstool/compare/v0.4.0...v0.5.0

## Version v0.4.0 - 2025-02-24

### What's Changed

* Fix | Add support for multipart body by @craigpotter in https://github.com/saloonphp/barstool/pull/15
* Fix | Change Migration Stub for URL  by @craigpotter in https://github.com/saloonphp/barstool/pull/16
* Update README.md with Prune instructions  by @craigpotter in https://github.com/saloonphp/barstool/pull/17

**Full Changelog**: https://github.com/saloonphp/barstool/compare/v0.3.0...v0.4.0

## Version v0.3.0 - 2025-02-24

### What's Changed

* Feature | Add Laravel 12 support by @craigpotter in https://github.com/saloonphp/barstool/pull/14

**Full Changelog**: https://github.com/saloonphp/barstool/compare/v0.2.0...v0.3.0

## v0.2.0 - 2025-02-06

### What's Changed

* Bump aglipanci/laravel-pint-action from 2.4 to 2.5 by @dependabot in https://github.com/saloonphp/barstool/pull/13
* PStan level 8 by @BinaryKitten in https://github.com/saloonphp/barstool/pull/12

### New Contributors

* @BinaryKitten made their first contribution in https://github.com/saloonphp/barstool/pull/12

**Full Changelog**: https://github.com/saloonphp/barstool/compare/v0.1.2...v0.2.0

## Version v0.1.2 - 2025-01-31

### What's Changed

* Fix | Update Migration DB Connection by @craigpotter in https://github.com/saloonphp/barstool/pull/11

**Full Changelog**: https://github.com/saloonphp/barstool/compare/v0.1.1...v0.1.2

## Version v0.1.1 - 2025-01-31

### What's Changed

* Feature | PHP8.4 by @craigpotter in https://github.com/saloonphp/barstool/pull/10

### New Contributors

* @craigpotter made their first contribution in https://github.com/saloonphp/barstool/pull/10

**Full Changelog**: https://github.com/saloonphp/barstool/compare/v0.1.0...v0.1.1

## v0.1.0 - 2025-01-30

Initial Version

**Full Changelog**: https://github.com/saloonphp/barstool/commits/v0.1.0
