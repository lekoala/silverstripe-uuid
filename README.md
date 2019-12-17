SilverStripe Uuid module
==================
[![Build Status](https://travis-ci.org/lekoala/silverstripe-uuid.svg?branch=master)](https://travis-ci.org/lekoala/silverstripe-uuid)
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

[![Dependency Status](https://www.versioneye.com/php/lekoala:silverstripe-uuid/badge.svg)](https://www.versioneye.com/php/lekoala:silverstripe-uuid)
[![Reference Status](https://www.versioneye.com/php/lekoala:silverstripe-uuid/reference_badge.svg?style=flat)](https://www.versioneye.com/php/lekoala:silverstripe-uuid/references)

![codecov.io](https://codecov.io/github/lekoala/silverstripe-uuid/branch.svg?branch=master)

Easily add uuid to your DataObjects

Simply add the extension to your DataObject

	MyDataObject:
		extensions:
			- LeKoala\Uuid\UuidExtension

Call UuidExtension::getByUuid(MyDataObject::class, $uuid) to retrieve the record by Uuid

In your templates, use UuidSegment to ensure Uuid value is generated on the record

Compatibility
==================
Tested with 4.1 up to 4.4

Maintainer
==================
LeKoala - thomas@lekoala.be
