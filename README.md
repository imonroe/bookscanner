# bookscanner

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Give Bookscanner a barcode or ISBN number, look it up via the OpenLibrary api (http://openlibrary.org/), and then return you a nice PHP array with relevant details.

Works with 10-digit or 13-digit ISBNs.  It will happily take input from a text field that has been populated by a standard handheld barcode scanner.  It will clean it up a little, validate the ISBN with its checksum, and then send a request to the excellent OpenLibrary API via cURL. It will parse the response, and it will return you a PHP array with all the available information.

For fields with more that one element, e.g., a book with several authors, the field will be returned as a comma-separated string.


## Install

Via Composer

``` bash
$ composer require imonroe/bookscanner
```

## Usage

``` php
$isbn_data = imonroe\bookscanner\Bookscanner::get_isbn_data($isbn_string); // from a text input, presumably a scanned barcode

$isbn = $isbn_data['isbn'];  // formatted ISBN number
$info_url = $isbn_data['info_url'];  // OpenLibrary URL to information page
$title = $isbn_data['title'];  // The title of the work
$subjects = $isbn_data['subjects']; // A string of subjects, separated by commas
$publisher = $isbn_data['publisher'];  // The publisher of the work
$author = $isbn_data['author']; // The author. If there are multiple, this will be a comma-delimited string
$publication_date = $isbn_data['publication_date'];  // The date the work was published
$number_of_pages =$isbn_data['number_of_pages'];  // Number of pages
$physical_format =$isbn_data['physical_format'];  // A string describing the physical format
$thumbnail_url = $isbn_data['thumbnail_url'];  // A URL to OpenLibrary's cover thumbnail.
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email ian@ianmonroe.com instead of using the issue tracker.

## Credits

- [Ian Monroe][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/imonroe/bookscanner.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/imonroe/bookscanner/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/imonroe/bookscanner.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/imonroe/bookscanner.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/imonroe/bookscanner.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/imonroe/bookscanner
[link-travis]: https://travis-ci.org/imonroe/bookscanner
[link-scrutinizer]: https://scrutinizer-ci.com/g/imonroe/bookscanner/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/imonroe/bookscanner
[link-downloads]: https://packagist.org/packages/imonroe/bookscanner
[link-author]: https://github.com/imonroe
[link-contributors]: ../../contributors
