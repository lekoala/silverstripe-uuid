<?php

namespace LeKoala\Uuid\Tests;

use LeKoala\Uuid\HasUuid;
use SilverStripe\Dev\TestOnly;
use SilverStripe\ORM\DataObject;
use LeKoala\Uuid\UuidExtension;

class Test_UuidModel extends DataObject implements TestOnly
{
    use HasUuid;

    private static $table_name = 'UuidModel';

    private static $db = [
        'Title' => 'Varchar',
        'UuidAlias' => 'Uuid',
    ];
    private static $extensions = [
        UuidExtension::class
    ];
}
