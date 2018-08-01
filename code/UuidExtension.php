<?php
namespace LeKoala\Uuid;

use Ramsey\Uuid\Uuid;
use SilverStripe\ORM\DB;
use InvalidArgumentException;
use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\FieldList;
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

    /**
     * Assign a new uuid to this record. This will overwrite any existing uuid.
     *
     * @param string $field The field where the Uuid is stored in binary format
     * @return string The new uuid
     */
    public function assignNewUuid($field = 'Uuid')
    {
        $uuid = Uuid::uuid4();
        $this->owner->Uuid = $uuid->getBytes();
        return $this->owner->Uuid;
    }

    /**
     * Get a record by its uuid
     *
     * @param string $class
     * @param string $uuid
     * @param string $format
     * @return DataObject
     */
    public static function getByUuid($class, $value, $format = null)
    {
        // Guess format from value
        if ($format === null) {
            $format = self::getUuidFormat($value);
        }
        // Convert format to bytes for query
        switch ($format) {
            case self::UUID_BASE62_FORMAT:
                $uuid = Uuid::fromBytes(Base62::decode($value));
                break;
            case self::UUID_STRING_FORMAT:
                $uuid = Uuid::fromString($value);
                break;
            case self::UUID_BINARY_FORMAT:
                $uuid = Uuid::fromBytes($value);
                break;
        }
        // Fetch the first record
        return $class::get()->filter('Uuid', $uuid->getBytes())->first();
    }

    /**
     * Guess uuid format based on strlen
     *
     * @param mixed $value
     * @return string
     */
    public static function getUuidFormat($value)
    {
        $len = strlen($value);

        if ($len == 36) {
             // d84560c8-134f-11e6-a1e2-34363bd26dae => 36 chars
            return self::UUID_STRING_FORMAT;
        } elseif ($len == 22) {
            // 6a630O1jrtMjCrQDyG3D3O => 22 chars
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

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();

        if (!$this->owner->Uuid) {
            $this->assignNewUuid();
        }
    }
}
