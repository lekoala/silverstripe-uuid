<?php

namespace LeKoala\Uuid\Tests;

use Ramsey\Uuid\Uuid;
use LeKoala\Uuid\DBUuid;
use LeKoala\Uuid\UuidExtension;
use SilverStripe\Dev\SapphireTest;

/**
 * Test for Uuid
 *
 * @group Uuid
 */
class UuidTest extends SapphireTest
{
    protected static $extra_dataobjects = [
        Test_UuidModel::class
    ];

    public function testRecordGetsUuid()
    {
        $model = new Test_UuidModel;

        $modelTitle = 'test model';
        $model->Title = $modelTitle;
        // Uuid is set by extension
        $model->write();

        $this->assertEquals($modelTitle, $model->Title);
        $this->assertNotEmpty($model->Uuid);

        // check if we can fetch this record using our helper
        $fetchedModel = UuidExtension::getByUuid(Test_UuidModel::class, $model->Uuid);
        $this->assertEquals($model->ID, $fetchedModel->ID);
    }

    public function testFormatting()
    {
        $uuid = 'd84560c8-134f-11e6-a1e2-34363bd26dae';
        $base62 = '6a630O1jrtMjCrQDyG3D3O';
        $binary = Uuid::fromString($uuid)->getBytes();

        $model = new Test_UuidModel;
        // Manually assign a uuid
        $model->Uuid = $binary;
        $model->write();

        /* @var $dbUuid DBUuid */
        $dbUuid = $model->dbObject('Uuid');

        $this->assertEquals($uuid, $dbUuid->Nice());
        $this->assertEquals($base62, $dbUuid->Base62());
        $this->assertEquals($base62, $model->UuidSegment());
        $this->assertEquals($binary, $model->Uuid);
        $this->assertEquals($binary, $dbUuid->Bytes());

        $this->assertEquals(UuidExtension::UUID_BASE62_FORMAT, UuidExtension::getUuidFormat($base62));
        $this->assertEquals(UuidExtension::UUID_BINARY_FORMAT, UuidExtension::getUuidFormat($binary));
        $this->assertEquals(UuidExtension::UUID_STRING_FORMAT, UuidExtension::getUuidFormat($uuid));

        // A uuid that results to 21 chars
        $uuid = '037961c2-122d-431f-9b34-4f8506b55ce7';
        $base62 = '6YRobjF5RORzHeaX6fvCZ';
        $binary = Uuid::fromString($uuid)->getBytes();

        $model = new Test_UuidModel;
        // Manually assign a uuid
        $model->Uuid = $binary;
        $model->write();

        /* @var $dbUuid DBUuid */
        $dbUuid = $model->dbObject('Uuid');
        $this->assertEquals($base62, $dbUuid->Base62());
    }
}
