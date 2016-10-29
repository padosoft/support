# Changelog

All Notable changes to `support` will be documented in this file

## 1.10.0 - 2016-10-29

### ADDED:
##### VALIDATION:
- isIPv4Compatibility() : Check if a string is a valid IP v4 compatibility (ffff:ffff:ffff:ffff.192.168.0.15).

##### IP:
- anonimizeIpv4() : masquerade last digit of IPv4 address.
- anonimizeIpv4Compatibility() : masquerade last digit of IPv4 compatibility address.
- anonimizeIpv6() : masquerade last digit of IPv6 address.
- anonimizeIpWithInet() : masquerade last digit of IP address with inet php function.
- expandIPv6Notation(): * Replace '::' with appropriate number of ':0'

### IMPROVE
##### IP:
- anonimizeIp(): now support ipv6 and ipv4 compatibility.

## 1.9.1 - 2016-09-18

### FIX:
- starts_with
- starts_with_insensitive
- array_get

## 1.9.0 - 2016-09-17

### ADDED:
SANITIZE:
- she() : Escape Shell argument.

### FIX
- one unit test.

## 1.8.0 - 2016-09-17
### ADDEDD:
STRING:
- str_word_count_utf8
ARRAY:
- insert_at_top
HELPER:
- windows_os

## 1.7.0 - 2016-09-14
### CHANGES:
STRING:
- str_replace_multiple_space now accept new optional argument to replace &nbsp;
VALIDATION:
- isTimeIso: check if string format is ok but number is out of range of valid date (i.e.: 24:88:99 is now return false). 

### ADDED:
VALIDATION:
- isDateZeroIso
- isTimeZeroIso
- isDateTimeZeroIso
- isDateOrDateZeroIso
- isDateTimeOrDateTimeZeroIso

## 1.6.0 - 2016-09-09
ADDED functions:
### helper:
- template
- randomChance
- getExceptionTraceAsString
### sanitize:
- csse
- attre
### validation:
- isPercent

### reflection:
- getClassNameFromFile
- getNamespaceFromFile
- getPhpDefinitionsFromFile

## 1.5.0 - 2016-09-09
FIXED:
### validation:
- dateItaToIso

ADDED functions:
### validation:
- isInRange
- isDay
- isMonth
- isJewishLeapYear
- betweenDateIso
- betweenDateIta
add more tests.

## 1.4.4 - 2016-09-08
CHANGES functions:
 ### array:
- refactor of arrayToObject and arrayToObject tests.

## 1.4.3 - 2016-09-07
ADD functions:
 ### datetime:
- cal_days_in_month
- cal_days_in_current_month
- days_in_month
- days_in_current_month

## 1.4.2 - 2016-09-02
ADD functions:
 ### datetime:
- ampm
- ampm2Number
- fuzzySpan
- unixTimestamp2dos
- dos2unixTimestamp

### helpers:
- isImageExtension
- getImageExtensions

## 1.4.1 - 2016-08-30

### ADD these new functions:

- starts_with_insensitive
- str_contains_insensitive
- str_finish_insensitive
- ends_with_insensitive
- getReferer
- getCurrentUrlPageName
- getCurrentUrlQuerystring
- getCurrentUrlDirName
- etCurrentUrlDirAbsName
- str_html_compress
- isZlibOutputCompressionActive
- isZlibLoaded
- isClientAcceptGzipEncoding
- compressHtmlPage
- get_http_response_code
- url_exists
- isNullOrEmptyArrayKey
- isNotNullOrEmptyArrayKey
- isNotNullOrEmptyArray
- startLayoutCapture
- endLayoutCapture
- get_var_dump_output
- logToFile
- curl_internal_server_behind_load_balancer

### CHANGES

- isHttps now support HTTP_X_FORWARDED_PROTO
- isNullOrEmpty now have a withTrim option
- isNotNullOrEmpty now have a withTrim option
- curl(): add more power to curl function!

## 1.4.0 - 2016-08-27

- ADD new functions: constants, objectToArray, getFaviconUrl, str_limit now have an option to preserve words, number to word and time to word functions, isMail now ha an option to check MX record is valid, .
- ADD many unit test.
- CHANGE small refactoring.
- CHANGE readme: add a full list of functions and constants.
- FIX rgb2hex.

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
