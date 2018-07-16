<?php
namespace LeKoala\Uuid\Tests;

use SilverStripe\Dev\TestOnly;
use SilverStripe\ORM\DataObject;
use LeKoala\Uuid\DBUuid;
use LeKoala\Uuid\UuidExtension;

class UuidModel extends DataObject implements TestOnly
{
    private static $table_name = 'UuidModel';

    private static $db = [
        'Title' => 'Varchar',
        'UuidAlias' => 'Uuid',
    ];
    private static $extensions = [
        UuidExtension::class
    ];
}
