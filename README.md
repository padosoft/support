# Generate slugs when saving Eloquent models

[![Latest Version on Packagist](https://img.shields.io/packagist/v/padosoft/support.svg?style=flat-square)](https://packagist.org/packages/padosoft/support)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/padosoft/support/master.svg?style=flat-square)](https://travis-ci.org/padosoft/support)
[![Quality Score](https://img.shields.io/scrutinizer/g/padosoft/support.svg?style=flat-square)](https://scrutinizer-ci.com/g/padosoft/support)
[![Total Downloads](https://img.shields.io/packagist/dt/padosoft/support.svg?style=flat-square)](https://packagist.org/packages/padosoft/support)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/236930cb-61cc-433f-b864-e5660f4533e6.svg?style=flat-square)](https://insight.sensiolabs.com/projects/a56f8c11-331f-4d3c-8724-77f55969f2f7)

This package provides a lot of agnostic helpers to use as foundation in packages and other project.

**NOTE:**
Some of these helpers was founded on the opensource web and some is adjusted for our purpose or for improvements.

##Overview

All helpers function are splitted into these files:

- Array
- Constants (generic usefull constants)
- DateTime
- Helpers (misc functions)
- Reflection
- String
- Validation

##Requires
  
- php: >=7.0.0
- nesbot/carbon
  
## Installation

You can install the package via composer:
``` bash
$ composer require padosoft/support
```

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
if( !isCF("") )  throw new Exception();
if( !isCF("abcdefghijklmnoz") )  throw new Exception();
if( !isCF("xxxxxx12c34x567o") )  throw new Exception();

//check italian VAT (Partita iva)
if( !isPIVA("") )  throw new Exception();
if( !isCF("00000000000") )  throw new Exception();
if( !isCF("02361141209") )  throw new Exception();
if( !isCF("00000000001") )  throw new Exception();

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
echo str_random(16);
echo str_random(16);
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
