<?php

namespace LeKoala\Uuid;

/**
 * Happy IDEs with this trait :-)
 */
trait HasUuid
{
    /**
     * @param string $value
     * @param string $format
     * @return static
     */
    public static function byUuid($value, $format = null)
    {
        return UuidExtension::getByUuid(get_called_class(), $value, $format);
    }
}
