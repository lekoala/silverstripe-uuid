<?php

use Ramsey\Uuid\Uuid;
use Tuupola\Base62Proxy as Base62;

class UuidExtension extends DataExtension
{
	private static $db = [
		"Uuid" => "DBUuid"
	];

	public function updateCMSFields(\FieldList $fields)
	{
		$fields->removeByName('Uuid');
	}

	public function assignNewUuid()
	{
		$uuid = Uuid::uuid4();
		$this->owner->Uuid = $uuid->getBytes();
	}

	/**
	 * Get a record by its uuid
	 *
	 * @param string $class
	 * @param string $uuid
	 * @param bool $fromBase62
	 * @return DataObject
	 */
	public static function getByUuid($class, $value, $fromBase62 = true)
	{
		if ($fromBase62) {
			$uuid = Uuid::fromBytes(Base62::decode($value));
		} else {
			$uuid = Uuid::fromString($value);
		}
		return $class::get()->filter('Uuid', $uuid->getBytes())->first();
	}

	/**
	 * Return a uuid suitable for an URL, like an URLSegment
	 *
	 * @return string
	 */
	public function UuidSegment()
	{
		if (!$this->owner->Uuid) {
			$this->assignNewUuid();
			$this->owner->write();
		}
		return $this->owner->dbObject('Uuid')->Base62();
	}

	public function onBeforeWrite()
	{
		parent::onBeforeWrite();

		if (!$this->owner->Uuid) {
			$this->assignNewUuid();
		}
	}
}
