<?php

require_once(__DIR__ . '/../../../lib/base.php');

use \OCA\user_shibboleth\DB as DB;

class DBTest extends PHPUnit_Framework_TestCase {
	
	private static $userX;
	private static $userY;
	private static $userZ;
	
	private static function cleanUpDatabase() {
		DB::deleteUser(self::$userX['LoginName']);
		DB::deleteUser(self::$userY['LoginName']);
		DB::deleteUser(self::$userZ['LoginName']);
	}
	
	public static  function setUpBeforeClass() {
		self::$userX = array('LoginName' => 'MisterX',
			'DisplayName' => 'mister.x@example.com',
			'HomeDir' => '/dev/null/shibboleth/MisterX');
		self::$userY = array('LoginName' => 'MisterY',
			'DisplayName' => 'mister.y@example.com',
			'HomeDir' => '/dev/null/shibboleth/MisterY');
		self::$userZ = array('LoginName' => 'MisterZ',
			'DisplayName' => 'mister.z@example.com',
			'HomeDir' => '/dev/null/shibboleth/MisterZ');
		self::cleanUpDatabase();//in case tearDownAfter was not called due to error
		DB::addUser(self::$userX['LoginName'], self::$userX['DisplayName'], self::$userX['HomeDir']);
	}

	public static function tearDownAfterClass() {
		self::cleanUpDatabase();
	}
	
	public function testAddUser() {
		$outcome = DB::addUser(self::$userY['LoginName'], self::$userY['DisplayName'], self::$userY['HomeDir']);
		$this->assertTrue($outcome);
		$outcome = DB::addUser(self::$userZ['LoginName'], self::$userZ['DisplayName'], self::$userZ['HomeDir']);
                $this->assertTrue($outcome);
	}

	public function testDeleteUser() {//run after testAddUser()
		$outcome = DB::deleteUser(self::$userZ['LoginName']);
                $this->assertTrue($outcome);
	}
	
	public function testLoginNameExists() {
		$outcome = DB::loginNameExists(self::$userX['LoginName']);
                $this->assertTrue($outcome);
		$outcome = DB::loginNameExists(self::$userZ['LoginName']);
		$this->assertFalse($outcome);
	}
	
	public function testLoginOrDisplayNameExists() {
                $outcome = DB::loginOrDisplayNameExists(self::$userX['LoginName']);
                $this->assertTrue($outcome);
                $outcome = DB::loginOrDisplayNameExists(self::$userX['DisplayName']);
                $this->assertTrue($outcome);
		$outcome = DB::loginOrDisplayNameExists(self::$userZ['LoginName']);
		$this->assertFalse($outcome);
		$outcome = DB::loginOrDisplayNameExists(self::$userZ['DisplayName']);
		$this->assertFalse($outcome);
        }

	public function testGetDisplayName() {
		$displayName = DB::getDisplayName(self::$userX['LoginName']);
		$this->assertEquals($displayName, self::$userX['DisplayName']);
		$displayName = DB::getDisplayName(self::$userZ['LoginName']);
                $this->assertFalse($displayName);
	}
	
	public function testGetHomeDir() {
		$homeDir = DB::getHomeDir(self::$userX['LoginName']);
                $this->assertEquals($homeDir, self::$userX['HomeDir']);
                $homeDir = DB::getDisplayName(self::$userZ['LoginName']);
                $this->assertFalse($homeDir);
	}
	
	public function testUpdateDisplayName() {
		DB::updateDisplayName(self::$userY['LoginName'], self::$userZ['DisplayName']);
		$displayName = DB::getDisplayName(self::$userY['LoginName']);
		$this->assertEquals($displayName, self::$userZ['DisplayName']);
		$outcome = DB::updateDisplayName(self::$userY['LoginName'], self::$userY['DisplayName']);//undo change
		$this->assertTrue($outcome);
	}
	
	public function testGetLoginNames() {
		//test based on login name
		$loginNames = DB::getLoginNames('Mister', 10, 0);
		$success = (in_array(self::$userX['LoginName'], $loginNames) && in_array(self::$userY['LoginName'], $loginNames));
		$this->assertTrue($success);
	}
	
	public function testGetDisplayNames() {
		$result = DB::getDisplayNames('mister', 10, 0);
		$this->assertEquals($result[self::$userX['LoginName']], self::$userX['DisplayName']);
		$this->assertEquals($result[self::$userY['LoginName']], self::$userY['DisplayName']);
	}

}
