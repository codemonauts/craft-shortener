# Release Notes for URL Shortener

## 1.0.0-alpha.4

### Added

- Add event HandleMissingShortUrl to allow plugins to handle request before a 404 exception is throwing on missing short URLs.
- Add QR Codes to Short URLs.
- The domain in the settings has to be a full URL.
- Add URL validation to domain.

## 1.0.0-alpha.3

### Fixed

- Fixed a bug when creating search index for ShortUrls without templates.
- Fix notice when saving new ShortURL.
- Trim domain and path for trailing slashes.

## 1.0.0-alpha.2

### Added

- Add redirect for non-codes and root path.

## 1.0.0-alpha.1

### Added

- Initial release
