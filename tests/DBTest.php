<?php

require_once(__DIR__ . '/../../../lib/base.php');

class DBTest extends PHPUnit_Framework_TestCase {

	public function testGetUsers() {
		$names = \OCA\user_shibboleth\DB::getUsers('ShibbolethTestUser', 10, 0);
		$this->assertTrue(in_array('ShibbolethTestUser', $names, true));
	}

	public function testUserExists() {
		//get a user that's definitely in the table
		$exists = \OCA\user_shibboleth\DB::userExists('ShibbolethTestUser');
		$this->assertTrue($exists);
		//and try to get a user that is not
		$exists = \OCA\user_shibboleth\DB::userExists('Graf Zahl');
		$this->assertFalse($exists);
	}
}
