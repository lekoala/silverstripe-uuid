<?php

namespace LeKoala\Uuid\Tests;

use LeKoala\Uuid\HasUuid;
use SilverStripe\Dev\TestOnly;
use SilverStripe\ORM\DataObject;
use LeKoala\Uuid\UuidExtension;

class Test_UuidModel extends DataObject implements TestOnly
{
    use HasUuid;

    /**
     * @var string
     */
    private static $table_name = 'UuidModel';

    /**
     * @var array<string,string>
     */
    private static $db = [
        'Title' => 'Varchar',
        'UuidAlias' => 'Uuid',
    ];
    /**
     * @var array<clazss-string>
     */
    private static $extensions = [
        UuidExtension::class
    ];
}
