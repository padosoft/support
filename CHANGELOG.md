# Changelog

All Notable changes to `support` will be documented in this file

## 1.3.1 - 2016-08-25

- CHANGE add if(!function_exists()) for some existing laravel functions and others to skip conflict.

## 1.3.0 - 2016-08-24

- ADD PHP 7.1 support.
- ADD sanitize helpers.
- ADD new validation helpers.
- ADD new helpers.
- CHANGE small changes and refactor.

## 1.2.0 - 2016-08-24

- ADD IP helpers.
- ADD getClientIp() by small refactor of Synfony functions with unit test.
- CHANGE mark OBSOLETE getIPVisitor().
- ADD isVATNumber().Validate a European VAT number using the EU commission VIES service with soap.
- ADD xml2array() and array2xml().
- ADD generateRandomPassword().
- ADD some new helpers.
- ADD sensiolabs_security_checker in scrutinizer config.
- FIX in xmlUrl2array.
- COMMENT ocular code-coverage:upload to scritinizer in travis config.

## 1.1.0 - 2016-08-21

- FIX isFloatingPoint()
- ADD more helpers.

## 1.0.0 - 2016-08-18

- Initial release
