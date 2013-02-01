<?php

require_once(__DIR__ . '/../../../lib/base.php');

class UtilsTest extends PHPUnit_Framework_TestCase {

	public function testEndsWith() {
		$suffix = '@example.com';
		
		$ew = \OCA\user_shibboleth\Utils::endsWith('tom@example.com', $suffix);
		$this->assertTrue($ew);
		
		$ew = \OCA\user_shibboleth\Utils::endsWith('tom@bad-example.com', $suffix);
                $this->assertFalse($ew);
		
		$ew = \OCA\user_shibboleth\Utils::endsWith('tom@Example.com', $suffix, true);
                $this->assertTrue($ew);
                
                $ew = \OCA\user_shibboleth\Utils::endsWith('tom@Example.com', $suffix, false);
                $this->assertFalse($ew);
	}

}
