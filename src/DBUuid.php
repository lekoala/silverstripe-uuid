<?php

namespace LeKoala\Uuid;

use Ramsey\Uuid\Uuid;
use SilverStripe\ORM\DB;
use Tuupola\Base62Proxy;
use SilverStripe\Core\Convert;
use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\FormField;
use SilverStripe\ORM\FieldType\DBField;

/**
 * A uuid field that stores Uuid in binary formats
 *
 * Some knowledge...
 *
 * @link https://paragonie.com/blog/2015/09/comprehensive-guide-url-parameter-encryption-in-php
 * @link https://www.percona.com/blog/2014/12/19/store-uuid-optimized-way/
 * @link https://mariadb.com/kb/en/library/guiduuid-performance/
 * @link https://stackoverflow.com/questions/28251144/inserting-and-selecting-uuids-as-binary16
 */
class DBUuid extends DBField
{
    const BINARY_LENGTH = 16;
    const STRING_LENGTH = 36;

    /**
     * An expression to use in your custom queries
     *
     * @link https://stackoverflow.com/questions/37168797/how-to-format-uuid-string-from-binary-column-in-mysql-mariadb
     * @return string
     */
    public static function sqlFormatExpr()
    {
        $sql = <<<SQL
LOWER(CONCAT(
SUBSTR(HEX(Uuid), 1, 8), '-',
SUBSTR(HEX(Uuid), 9, 4), '-',
SUBSTR(HEX(Uuid), 13, 4), '-',
SUBSTR(HEX(Uuid), 17, 4), '-',
SUBSTR(HEX(Uuid), 21)
)) AS UuidFormatted
SQL;
        return $sql;
    }

    /**
     * This can be used with ->where clause
     *
     * @param string $type like, =
     * @param string $value
     * @return string
     */
    public function filterExpression($type, $value)
    {
        if ($type == "like") {
            $value = "%$value%";
        }
        $value = str_replace('-', '', $value);
        /** @var string $value */
        $value = Convert::raw2sql($value, true);
        return "LOWER(HEX({$this->name})) $type $value";
    }

    /**
     * @return void
     */
    public function requireField()
    {
        // Use direct sql statement here
        $sql = "binary(16)";
        // In postgres, it's bytea, there is also an uuid but we would need some postgres specific logic
        // @link https://stackoverflow.com/questions/26990559/convert-mysql-binary-to-postgresql-bytea
        $class = strtolower(get_class(DB::get_conn()));
        if (strpos($class, 'postgres') !== false) {
            $sql = 'bytea';
        }
        DB::require_field($this->tableName, $this->name, $sql);
    }

    /**
     * @return string A uuid identifier like 0564a64ecdd4a2-7731-3233-3435-7cea2b
     */
    public function Nice()
    {
        if (!$this->value) {
            return $this->nullValue();
        }
        return Uuid::fromBytes($this->value)->toString();
    }

    /**
     * Return raw value since we store binary(16) representation
     *
     * @return string The binary representation like b"\x05d¦NÍÔ¢w12345|ê+
     */
    public function Bytes()
    {
        if (!$this->value) {
            return $this->nullValue();
        }
        return $this->value;
    }

    /**
     * Perfect for urls or html usage
     *
     * @return string A base62 representation like 6a630O1jrtMjCrQDyG3D3O
     */
    public function Base62()
    {
        if (!$this->value) {
            return $this->nullValue();
        }
        return Base62Proxy::encode($this->value);
    }

    /**
     * @param string $title
     * @param array<mixed> $params
     * @return FormField|null
     */
    public function scaffoldFormField($title = null, $params = null)
    {
        return null;
    }

    public function nullValue()
    {
        return null;
    }

    /**
     * @param mixed $value
     * @param DataObject|array<string,mixed> $record
     * @param boolean $markChanged
     * @return $this
     */
    public function setValue($value, $record = null, $markChanged = true)
    {
        if ($value && is_string($value) && strlen($value) > self::BINARY_LENGTH && Uuid::isValid($value)) {
            $value = Uuid::fromString($value)->getBytes();
        }
        return parent::setValue($value, $record, $markChanged);
    }

    /**
     * @param string $value
     * @return string|null
     */
    public function prepValueForDB($value)
    {
        if (!$value) {
            return $this->nullValue();
        }
        // Uuid in string format have 36 chars
        // Strlen 16 = already binary
        if (strlen($value) === self::BINARY_LENGTH) {
            return $value;
        }
        return Uuid::fromString($value)->getBytes();
    }
}
