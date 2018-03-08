<?php

/**
 * Test for Uuid
 *
 * @group Uuid
 */
class UuidTest extends SapphireTest
{

	public function testRecordGetsUuid() {
		$member = new Member();
		if(!$member->has_extension(UuidExtension::class)) {
			$this->skipTest();
		}

		$member->Email = 'uuid@test.com';
		$member->write();

		$this->assertNotEmpty($member->Uuid);
	}
}
