SilverStripe Uuid module
==================

Easily add uuid to your DataObjects

Simply add the extension to your DataObject

	MyDataObject:
		extensions:
			- UuidExtension

Call UuidExtension::getByUuid(MyDataObject::class, $uuid) to retrieve the record by Uuid

In your templates, use UuidSegment to ensure Uuid value is generated on the record

Note: version for SilverStripe 4 coming soon!

Compatibility
==================
Tested with 3.6

Maintainer
==================
LeKoala - thomas@lekoala.be
