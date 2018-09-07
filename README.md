# Collection of agnostic PHP Functions and helpers

[![Latest Version on Packagist](https://img.shields.io/packagist/v/padosoft/support.svg?style=flat-square)](https://packagist.org/packages/padosoft/support)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/padosoft/support/master.svg?style=flat-square)](https://travis-ci.org/padosoft/support)
[![Quality Score](https://img.shields.io/scrutinizer/g/padosoft/support.svg?style=flat-square)](https://scrutinizer-ci.com/g/padosoft/support)
[![Total Downloads](https://img.shields.io/packagist/dt/padosoft/support.svg?style=flat-square)](https://packagist.org/packages/padosoft/support)

This package provides a lot of very usefull agnostic helpers to use as foundation in packages and other project.

**NOTE:**
Some of these helpers was written by padosoft, another was founded on the opensource web and some of these is refactored and/or adjusted for our purpose or for improvements.

##Overview

All helpers function are splitted into these files:

- Array
- Constants (generic usefull constants)
- DateTime
- Helpers (misc functions)
- IP
- Reflection
- Sanitize
- String
- Validation
- Xml

##Requires
  
- php: >=7.0.0
- nesbot/carbon (only for some datetime functions)
  
## Installation

You can install the package via composer:
```bash
$ composer require padosoft/support
```

## Usage

Create new php file, add composer autoload and start using functions.

```php
<?php

require "vendor/autoload.php";

var_dump(str_random(16));
```

## List of functions

### Array
- head
- last
- insert_at_top
- array_has
- array_get
- array_set
- CleanUpArrayOfInt
- array_split_filter
- in_array_column
- objectToArray
- arrayToObject
- arrayToString
- array_key_exists_safe
- array_get_key_value_safe
- isNullOrEmptyArray
- isNotNullOrEmptyArray
- isNullOrEmptyArrayKey
- isNotNullOrEmptyArrayKey
- array_remove_columns() : Remove given column from the subarrays of a two dimensional array.
- array_remove_first_columns() : Remove first column from the subarrays of a two dimensional array.
- array_remove_last_columns()  : Remove last column from the subarrays of a two dimensional array.

### DateTime
- carbonFromIsoDateTime
- carbonFromIsoDate
- carbonFromItaDateTime
- carbonFromItaDate
- carbon
- roman_year
- partialsDateIso
- dateIsoToIta
- dateItaToIso
- monthFromNumber
- dateIsoToItaSpec
- getNameDayFromDateIso
- getTimeFromDateTimeIso
- diff_in_year
- age
- ampm
- ampm2Number
- fuzzySpan
- unixTimestamp2dos
- dos2unixTimestamp
- cal_days_in_month
- cal_days_in_current_month
- days_in_month
- days_in_current_month


### Helpers (misc functions)
- rgb2hex
- hex2rgb
- format_money
- format_euro
- ordinal
- value
- with
- setErrorReportingForProduction
- isExecutedByCLI
- bytes2HumanSize
- convertPHPSizeToBytes
- getMaximumFileUploadSize
- encryptString
- getFaviconUrl
- getFaviconImgTag
- isHttps
- getQRcode
- getQRcodeUrl
- gravatarUrl
- gravatar
- isNumberOdd
- isNumberEven
- getTinyUrl
- expandShortUrl
- curl
- curl_internal_server_behind_load_balancer
- debug
- isAjax
- isMobile
- getBrowser
- getReferer
- getCurrentURL
- getCurrentUrlPageName
- getCurrentUrlQuerystring
- getCurrentUrlDirName
- getCurrentUrlDirAbsName
- isZlibOutputCompressionActive
- isZlibLoaded
- isClientAcceptGzipEncoding
- compressHtmlPage
- get_http_response_code
- url_exists
- startLayoutCapture
- endLayoutCapture
- get_var_dump_output
- logToFile
- isImageExtension
- getImageExtensions
- template
- randomChance
- getExceptionTraceAsString
- windows_os
- getConsoleColorTagForStatusCode() : Get the color tag for the given status code to be use in symfony/laravel console.
- get_os_architecture() : Get the OS architecture 32 or 64 bit.
- is_32bit() : Check if the OS architecture is 32bit.
- is_64bit() : Check if the OS architecture is 64bit.
- isRequestFromCloudFlare(): check if request (by given $_SERVER) is a cloudflare request.
- isCloudFlareIp(): check if given ip is a valid cloudflare ip. 
- gzCompressFile(): GZIPs a file on disk (appending .gz to the name) without read all source file in memory.

### IP
- getIPVisitor
- anonimizeIp
- anonimizeIpv4() : masquerade last digit of IPv4 address.
- anonimizeIpv4Compatibility() : masquerade last digit of IPv4 compatibility address.
- anonimizeIpv6() : masquerade last digit of IPv6 address.
- anonimizeIpWithInet() : masquerade last digit of IP address with inet php function.
- getHost
- getClientIps
- getClientIp
- checkIp
- checkIp4
- checkIp6
- isFromTrustedProxy
- expandIPv6Notation(): * Replace '::' with appropriate number of ':0'
- decbin32(): to ensure that the binary strings are padded with zeros out to 32 characters.
- ipInRange(): Function to determine if an IP is located in a specific range as specified via several alternative formats.

### Reflection
- short_class_name
- class_constants
- class_uses_recursive
- class_basename
- getClassNameFromFile
- getNamespaceFromFile
- getPhpDefinitionsFromFile

### Sanitize
- strip_nl
- jse : Escape javascript argument.
- e : Escape html argument.
- csse : Escape css argument.
- attre : Escape html attribute argument.
- she() : Escape shell argument.
- normalizeUtf8String : Normalize the string. Removes all diacritics (marks like accents) from a given UTF8-encoded texts and returns ASCii-text.
- normalizerUtf8Safe() : Normalize uft8 to various form with php normalizer function if exists, otherwise return original string.
- sanitize_filename
- sanitize_pathname
- sanitize_arr_string_xss
- sanitize_string_xss
- sanitize_urlencode
- sanitize_email
- sanitize_numbers
- sanitize_floats
- sanitize_phone() : Sanitize the string by removing illegal characters from phone numbers.

### String

- generateRandomPassword
- generateRandomString
- preg_replace_sub
- snake_case
- str_random
- ends_with
- ends_with_insensitive
- starts_with
- starts_with_insensitive
- str_contains
- str_contains_insensitive
- str_finish
- str_finish_insensitive
- str_is
- str_limit
- str_replace_array
- studly_case
- studly
- camel_case
- underscore2dash
- dash2underscore
- str_replace_multiple_space
- str_replace_last
- segment
- firstSegment
- lastSegment
- isNullOrEmpty
- isNotNullOrEmpty
- numberToWord
- secondsToText
- minutesToText
- hoursToText
- str_html_compress
- str_word_count_utf8
- slugify() : Generate a URL friendly "slug" from a given string.
- firstStringBetween(): Returns the first string there is between the strings from the parameter start and end.

### Validation

- isStringNumberStartsWithMoreThanOneZero
- isIntegerPositive
- isIntegerPositiveOrZero
- isIntegerNegative() : Check if the value (int, float or string) is a integer and less than zero.
- isIntegerNegativeOrZero() : Check if the value (int, float or string) is a integer and less than zero or equals to zero.
- isIntegerZero() : Check if the value (int, float or string) is a integer and equals to zero.
- isInteger
- isIntegerFloatingPoint
- isFloatingPoint
- isDouble
- isPercent
- isIntBool() : Check if the value is a integer/string 0 or 1.
- isDateIta
- isDateIso
- isDateTimeIso
- isDateTimeIta
- isTimeIso
- isTimeIta
- isDateZeroIso() : Check if string is 0000-00-00
- isTimeZeroIso() : Check if string is 00:00:00
- isDateTimeZeroIso() : Check if string is '0000-00-00 00:00:00' 
- isDateOrDateZeroIso() : Check if string is YYYY-mm-dd and valid date or 0000-00-00 
- isDateTimeOrDateTimeZeroIso() : Check if string is 'YYYY-mm-dd HH:ii:ss' and valid date or '0000-00-00 00:00:00' 
- isDateZeroIta() : Check if string is 00/00/0000
- isTimeZeroIta() : Check if string is 00:00:00
- isDateTimeZeroIta() : Check if string is '00/00/0000 00:00:00' 
- isDateOrDateZeroIta() : Check if string is dd/mm/YYYY and valid date or 00/00/0000 
- isDateTimeOrDateTimeZeroIta() : Check if string is 'dd/mm/YYYY HH:ii:ss' and valid date or '00/00/0000 00:00:00' 
- hasMinAge
- hasMaxAge
- hasAgeInRange
- isInRange
- isDay
- isMonth
- isJewishLeapYear
- betweenDateIso
- betweenDateIta
- isMail
- isIPv4
- isIPv6
- isIPv4Compatibility
- isIP
- isUrl
- isHostname
- urlW3c
- isVATRegisteredInVies() : Validate a European VAT number using the EU commission VIES service to verify if VAT number is authorized to carry out intra-Community operations must use the service.
- isITVat() : Check Italian Vat Number (Partita IVA).
- isEuVatNumber() : Check if a valid EU vat given against specific EU country vat rules (at the moment only for italy) and optionally check the EU VIES service.
- getCoutryCodeByVatNumberTest($val, $fallback): Try to extract EU country code in Vat number return $fallback if it fails.   
- isCf
- isAlpha
- isAlphaNumeric
- isAlphaNumericDash
- isAlphaNumericWhiteSpaces
- isNumeric() : Determine if the provided value contains only numeric characters with or without(default) sign.
- isNumericWithSign() : Determine if the provided value contains only numeric characters with sign.
- isNumericWithoutSign() : Determine if the provided value contains only numeric characters without sign.
- isBool
- isBoolOrIntBool
- isCrediCard
- isValidHumanName
- isIban
- hasFileExtension
- isphoneNumber
- isJsonString
- isUuid
- isGeoCoordinate
- isLatitude
- isLongitude
- isAscii
- isUtf8

### Xml
- xmlUrl2array
- xml2array
- array2xml
- array2SimpleXMLElement

### Constants (generic usefull constants)
- DS
- NUMBERS_ITA_ARR
- NUMBERS_EN_ARR
- PERIOD_IN_SECONDS_ITA_ARR
- PERIOD_SINGULAR_PLURAL_ITA_ARR
- PERIOD_IN_SECONDS_EN_ARR
- SECOND_IN_SECOND
- MINUTE_IN_SECOND
- HOUR_IN_SECOND
- DAY_IN_SECOND
- WEEK_IN_SECOND
- MONTH_IN_SECOND
- YEAR_IN_SECOND
- DATE_TIME_FORMAT_ISO
- DATE_TIME_FORMAT_ITA
- DATE_FORMAT_ISO
- DATE_FORMAT_ITA
- TIME_FORMAT_ISO
- TIME_FORMAT_ITA
- SUNDAY
- MONDAY
- TUESDAY
- WEDNESDAY
- THURSDAY
- FRIDAY
- SATURDAY
- DAYS_ITA_ARR
- DAYS_ENG_ARR
- GENNAIO
- FEBBRAIO
- MARZO
- APRILE
- MAGGIO
- GIUGNO
- LUGLIO
- AGOSTO
- SETTEMBRE
- OTTOBRE
- NOVEMBRE
- DICEMBRE
- MONTHS_ITA_ARR
- MONTHS_ITA_ARR_1_BASED
- MONTHS_SHORT_ITA_ARR
- MONTHS_SHORT_ITA_ARR_1_BASED

## Usage

You can call every functions directly.
Example:

```php
<?php

/*
 *  constans
 */
echo 'directory separator is: '.DS;

/*
 *  validation helpers
 */

//check iso date
if( !isDateIso("") )  echo 'invalid.';
if( !isDateIso("2016-08-18") )  echo 'invalid.';
if( !isDateIso("2016-18-08") )  echo 'invalid.';
if( !isDateIso("0000-00-00") )  echo 'invalid.';
if( !isDateIso("00-00-00") )  echo 'invalid.';
if( !isDateIso("16-08-18") )  echo 'invalid.';
if( !isDateIso("2016-02-38") )  echo 'invalid.';

//check italian Fiscal Code
if( !isCf("") )  throw new Exception();
if( !isCf("abcdefghijklmnoz") )  throw new Exception();
if( !isCf("xxxxxx12c34x567o") )  throw new Exception();

//check italian VAT (Partita iva)
if( !isPiva("") )  throw new Exception();
if( !isCf("00000000000") )  throw new Exception();
if( !isCf("02361141209") )  throw new Exception();
if( !isCf("00000000001") )  throw new Exception();

//check integer value
if( !isInteger(1561) )  throw new Exception();
if( !isInteger('sadasd') )  throw new Exception();

/*
 *  datetime helpers
 */

//sleep 2 minuti
sleep(2*MINUTE_IN_SECOND);
//sleep 2h
sleep(2*HOUR_IN_SECOND);
//sleep 2min and 30seconds
sleep(2*MINUTE_IN_SECOND+30);

//date format
echo date(DATE_FORMAT_ISO);//'Y-m-d' 
echo date(DATE_FORMAT_ITA);//'d-m-Y'
echo date(DATE_TIME_FORMAT_ISO);//'Y-m-d H:i:s'

//date conversion
echo dateIsoToIta('2016-08-18');//08/18/2016

//days and month
echo DAYS_ITA_ARR[0];//Lunedi
echo DAYS_ITA_ARR[date('w')];
echo MONTHS_ITA_ARR_1_BASED[12];//Dicembre
echo MONTHS_ITA_ARR_1_BASED[date('j')];

//misc
echo roman_year(50);//L
echo roman_year(10);//X
echo roman_year(2000);//MM
echo roman_year(2016);//MMXVI

/**
* String
 */
echo str_random(16);

```

NOTA: for full list of helpers functions, see the code in /src. 


## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email instead of using the issue tracker.

## Credits
- [Lorenzo Padovani](https://github.com/lopadova)
- [All Contributors](../../contributors)

## About Padosoft
Padosoft (https://www.padosoft.com) is a software house based in Florence, Italy. Specialized in E-commerce and web sites.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
