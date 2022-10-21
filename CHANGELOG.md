# Release Notes for URL Shortener

## 2.0.4 - 2022-10-21

### Fixed

- Routing with schemes.

## 2.0.3 - 2022-06-29

### Fixed

- Fixed column order, again.

## 2.0.2 - 2022-06-29

### Fixed

- Fixed column order.

## 2.0.1 - 2022-06-29

### Fixed

- Fixed migration table name.

## 2.0.0 - 2022-06-27

### Added

- Craft CMS 4 compatibility

### Changed

- Requires Craft CMS >= 4.0

## 1.0.0-alpha-5 - 2021-06-23

### Added

- Add a description field to Short URLs.
- Add functions to delete templates and Short URLs.

### Fixed

- Fix notice when saving templates.
- Show template actions only, when templates are available.

## 1.0.0-alpha.4 - 2021-06-22

### Added

- Add event HandleMissingShortUrl to allow plugins to handle request before a 404 exception is throwing on missing short URLs.
- Add QR Codes to Short URLs.
- The domain in the settings has to be a full URL.
- Add URL validation to domain.

## 1.0.0-alpha.3 - 2021-06-21

### Fixed

- Fixed a bug when creating search index for ShortUrls without templates.
- Fix notice when saving new ShortURL.
- Trim domain and path for trailing slashes.

## 1.0.0-alpha.2 - 2020-08-18

### Added

- Add redirect for non-codes and root path.

## 1.0.0-alpha.1 - 2020-08-17

### Added

- Initial release
