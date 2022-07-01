# SilverStripe Uuid module

[![Build Status](https://travis-ci.com/lekoala/silverstripe-uuid.svg?branch=master)](https://travis-ci.com/lekoala/silverstripe-uuid)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/lekoala/silverstripe-uuid/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/lekoala/silverstripe-uuid/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/lekoala/silverstripe-uuid/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/lekoala/silverstripe-uuid/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/lekoala/silverstripe-uuid/badges/build.png?b=master)](https://scrutinizer-ci.com/g/lekoala/silverstripe-uuid/build-status/master)
[![codecov.io](https://codecov.io/github/lekoala/silverstripe-uuid/coverage.svg?branch=master)](https://codecov.io/github/lekoala/silverstripe-uuid?branch=master)

[![Latest Stable Version](https://poser.pugx.org/lekoala/silverstripe-uuid/version)](https://packagist.org/packages/lekoala/silverstripe-uuid)
[![Latest Unstable Version](https://poser.pugx.org/lekoala/silverstripe-uuid/v/unstable)](//packagist.org/packages/lekoala/silverstripe-uuid)
[![Total Downloads](https://poser.pugx.org/lekoala/silverstripe-uuid/downloads)](https://packagist.org/packages/lekoala/silverstripe-uuid)
[![License](https://poser.pugx.org/lekoala/silverstripe-uuid/license)](https://packagist.org/packages/lekoala/silverstripe-uuid)
[![Monthly Downloads](https://poser.pugx.org/lekoala/silverstripe-uuid/d/monthly)](https://packagist.org/packages/lekoala/silverstripe-uuid)
[![Daily Downloads](https://poser.pugx.org/lekoala/silverstripe-uuid/d/daily)](https://packagist.org/packages/lekoala/silverstripe-uuid)

Easily add uuid to your DataObjects

# Getting started

Simply add the extension to your DataObject

```yml
MyDataObject:
  extensions:
    - LeKoala\Uuid\UuidExtension
```

Call UuidExtension::getByUuid(MyDataObject::class, $uuid) to retrieve the record by Uuid.

# Usage in templates

In your templates, use UuidSegment to ensure Uuid value is generated on the record.
UuidSegment are base62 encoded in order to be shorter and more readable.

# Getting readable values

Since Uuid's are stored in binary format for performance reason, you need to call $myObject->dbObject('Uuid')->Nice()
to get a readable value.

# Upgrade to Ramsey v4

If you happen to upgrade from previous versions you might want to check [this guide](https://uuid.ramsey.dev/en/latest/upgrading/3-to-4.html)

# TODO

- Upgrade to Ramsey v4
- Postgres compat

# Worth reading

[Storing UUID Values in MySQL](https://www.percona.com/blog/2014/12/19/store-uuid-optimized-way/)
[GUID/UUID Performance](https://mariadb.com/kb/en/guiduuid-performance/)
[Laravel: The mysterious “Ordered UUID”](https://itnext.io/laravel-the-mysterious-ordered-uuid-29e7500b4f8): offer a good overview of the situation although it's a bit laravel specific

# Compatibility

Tested with 4.4 and up

# Maintainer

LeKoala - thomas@lekoala.be
