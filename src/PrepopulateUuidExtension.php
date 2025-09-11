<?php

namespace LeKoala\Uuid;

use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\DB;

/**
 * Add this extension to any class requiring pre-population of Uuids on dev/build
 */
class PrepopulateUuidExtension
    extends DataExtension
{
    /**
     * Invoked after every database build is complete (including after table creation and
     * default record population).
     *
     * See {@link DatabaseAdmin::doBuild()} for context.
     */
    public function onAfterBuild()
    {
        $emptyUuidItems = DataObject::get($this->owner->getClassName())->filter('Uuid', null);
        if($emptyUuidCount = $emptyUuidItems->count()){
            foreach ($emptyUuidItems as $item) {
                $item->UuidSegment();
            }
            DB::alteration_message("{$this->owner->getClassName()}: {$emptyUuidCount} empty Uuids prepopulated", 'changed');
        }
    }
}