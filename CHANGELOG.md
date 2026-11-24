# Changelog

Notable changes to this package. The format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/) and versions follow [Semantic Versioning](https://semver.org/).

## [4.0.0] - 2026-10-28

### Changed
- Requires PHP 8.3 or 8.4, Laravel 10 to 13 and `experteam/api-laravel-base` 3.x. The code is the same as 3.7.5.

## [3.7.5] - 2026-09-10

### Changed
- Model event constructors include the `session` in the user data.

## [3.7.4] - 2026-09-09

### Changed
- MongoDB audits log the `country_id`.

## [3.7.3] - 2026-08-04

### Changed
- The Redis connection for stream_compute is configurable with `STREAM_COMPUTE_REDIS`, `stream_compute` by default.

## [3.7.2] - 2026-08-01

### Changed
- `ModelChanged` tracks changes with `getDirty`.

## [3.7.1] - 2026-08-01

### Changed
- Model event constructors accept any user type and handle null values.

## [3.7.0] - 2026-07-31

### Added
- `ModelInserted` event, and audit records for inserted and deleted models. `BaseLogModel` writes them to the `ElasticSearch` log and to the `transaction_logs` table of the `mongodb_audits` connection.

## [3.6.1] - 2026-11-24

### Changed
- Accepts `experteam/api-laravel-base` 3.x as well as 2.x. The code is the same as 3.6.0.

### Notes
- Bridge release cut from 3.6.0, outside this branch, for APIs that need `experteam/api-laravel-base` 3.x without the 3.7 audit changes.

## [3.6.0] - 2025-11-18

### Added
- Laravel 12 support.

## Earlier versions

Versions before 3.6.0 are described by their git tags.
