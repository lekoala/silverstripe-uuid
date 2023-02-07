<?php

namespace LeKoala\Uuid;

use Ramsey\Uuid\Uuid;
use SilverStripe\ORM\DB;
use InvalidArgumentException;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\ReadonlyField;
use SilverStripe\ORM\DataObject;
use Tuupola\Base62Proxy as Base62;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\DataObjectSchema;

class UuidExtension extends DataExtension
{
    const UUID_BINARY_FORMAT = 'binary';
    const UUID_STRING_FORMAT = 'string';
    const UUID_BASE62_FORMAT = 'base62';

    private static $db = [
        "Uuid" => DBUuid::class,
    ];
    private static $indexes = [
        "Uuid" => true,
    ];

    /**
     * Assign a new uuid to this record. This will overwrite any existing uuid.
     *
     * @param bool $check Check if the uuid is already taken
     * @return string The new uuid
     */
    public function assignNewUuid($check = true)
    {
        $uuid = Uuid::uuid4();
        if ($check) {
            $schema = DataObjectSchema::create();
            $table = $schema->tableForField(get_class($this->owner), 'Uuid');
            do {
                $this->owner->Uuid = $uuid->getBytes();
                // If we have something, keep checking
                $check = DB::prepared_query('SELECT count(ID) FROM ' . $table . ' WHERE Uuid = ?', [$this->owner->Uuid])->value() > 0;
            } while ($check);
        } else {
            $this->owner->Uuid = $uuid->getBytes();
        }

        return $this->owner->Uuid;
    }

    /**
     * Get a record by its uuid
     *
     * @param string $class The class
     * @param string $uuid The uuid value
     * @param string $format Any UUID_XXXX_FORMAT constant or string
     * @return DataObject|false The DataObject or false if no record is found or format invalid
     */
    public static function getByUuid($class, $value, $format = null)
    {
        // Guess format from value
        if ($format === null) {
            try {
                $format = self::getUuidFormat($value);
            } catch (InvalidArgumentException $ex) {
                $format = null;
            }
        }
        // Convert format to bytes for query
        switch ($format) {
            case self::UUID_BASE62_FORMAT:
                try {
                    $decodedValue = Base62::decode($value);
                } catch (InvalidArgumentException $ex) {
                    // Invalid arguments should not return anything
                    return false;
                }
                $uuid = Uuid::fromBytes($decodedValue);
                break;
            case self::UUID_STRING_FORMAT:
                $uuid = Uuid::fromString($value);
                break;
            case self::UUID_BINARY_FORMAT:
                $uuid = Uuid::fromBytes($value);
                break;
            default:
                return false;
        }
        // Fetch the first record and disable subsite filter in a similar way as asking by ID
        $q = $class::get()->filter('Uuid', $uuid->getBytes())->setDataQueryParam('Subsite.filter', false);
        return $q->first();
    }

    /**
     * Guess uuid format based on strlen
     *
     * @param mixed $value
     * @return string
     * @throws InvalidArgumentException
     */
    public static function getUuidFormat($value)
    {
        $len = strlen((string)$value);

        if ($len == 36) {
            // d84560c8-134f-11e6-a1e2-34363bd26dae => 36 chars
            return self::UUID_STRING_FORMAT;
        } elseif ($len > 20 && $len < 24) {
            // 6a630O1jrtMjCrQDyG3D3O => 22 chars (in theory, because sometimes it's different)
            return self::UUID_BASE62_FORMAT;
        } elseif ($len == 16) {
            return self::UUID_BINARY_FORMAT;
        }
        throw new InvalidArgumentException("$value does not seem to be a valid uuid");
    }

    /**
     * Return a uuid suitable for an URL, like an URLSegment
     *
     * @return string
     */
    public function UuidSegment()
    {
        // assign on the fly
        if (!$this->owner->Uuid) {
            $uuid = $this->assignNewUuid();
            // Make a quick write without using orm
            if ($this->owner->ID) {
                $schema = new DataObjectSchema;
                $table = $schema->tableName(get_class($this->owner));
                DB::prepared_query("UPDATE $table SET Uuid = ? WHERE ID = ?", [$uuid, $this->owner->ID]);
            }
        }
        return $this->owner->dbObject('Uuid')->Base62();
    }

    public function updateCMSFields(FieldList $fields)
    {
        if (DBUuid::config()->show_cms_field) {
            $firstField = $fields->dataFieldNames()[0] ?? null;
            $fields->addFieldToTab('Root.Main', ReadonlyField::create('UuidNice', 'Uuid', $this->owner->dbObject('Uuid')->Nice()), $firstField);
        }
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();

        if (!$this->owner->Uuid) {
            $this->assignNewUuid();
        }
    }
}
