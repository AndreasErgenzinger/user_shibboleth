<?php
/**
 * ownCloud - user_shibboleth
 * 
 * Copyright (C) 2013 Andreas Ergenzinger andreas.ergenzinger@uni-konstanz.de
 *
 * This library is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this library. If not, see <http://www.gnu.org/licenses/>.
 */

namespace OCA\user_shibboleth;

require_once(__DIR__ . '/../../../lib/base.php');

/**
 * This class offers convenient access to the primary LDAP server used by the
 * LDAP user and group backend.
 */
class LdapBackendAdapter {

	private $enabled;
	private $connected = false;
	private $connection;
	private $ldap;


	function __construct() {
		$this->enabled = (\OCP\Config::getAppValue('user_shibboleth', 'link_to_ldap_backend', '0') === '1') &&
                        \OCP\App::isEnabled('user_shibboleth')  && \OCP\App::isEnabled('user_ldap');
	}


	private function connect() {
		if (!$this->connected) {
			$this->connection = new \OCA\user_ldap\lib\Connection();
			$this->ldap = new \OCA\user_ldap\USER_LDAP();
			$this->ldap->setConnector($this->connection);
			$this->connected = true;
		}
	}


	/**
	 * @brief returns true if and only if a user with the given uuid exists in the LDAP
	 * @param string a unique user identifier
	 * @return a boolean value
	 */
	public function uuidExists($uuid) {
		//check backend status
		if (!$this->enabled) {
			return false;
		}

		//check tables
		$query = \OCP\DB::prepare('SELECT COUNT(*) FROM *PREFIX*ldap_user_mapping WHERE owncloud_name = ?');
		$result = $query->execute(array($uuid));
		if (!\OCP\DB::isError($result)) {
			$count = $result->fetchAll(\PDO::FETCH_COLUMN, 0);
			if ($count[0] === 1) {
				return true;
			}
                }

		//check primary LDAP server
		$this->connect();		
		$filter = $this->connection->ldapUuidAttribute . '=' . $uuid;
		$result = $this->ldap->fetchListOfUsers($filter, $this->connection->ldapUuidAttribute);

		if (count($result) === 1 && $result[0]['count'] === 1) {
			return true;
		}
		return false;
	}


	public function getUuid($mail) {
		//check backend status
		if (!$this->enabled) {
			return false;
		}
		
		//retrieve UUID from LDAP server
		$this->connect();
		$attr = \OCP\Config::getAppValue('user_shibboleth', 'ldap_link_attribute', 'mail');
		$filter = $attr . '=' . $mail;
                $result = $this->ldap->fetchListOfUsers($filter, $this->connection->ldapUuidAttribute);

                if (count($result) === 1 && $result[0]['count'] === 1) {
                        return $result[0][0];
                }
                return false;
	}

	
	public function initializeUser($uuid) {
		//check backend status
                if (!$this->enabled) {
                        return false;
		}
		
		$this->connect();
		$filter = $this->connection->ldapUuidAttribute . '=' . $uuid;
		$users = $this->ldap->fetchListOfUsers($filter, 'dn');
		if (count($users) === 1 && $users[0]['count'] === 1) {
			$dn = $users[0][0];
			$this->ldap->dn2ocname($dn);//creates table entries and folders
			return true;
		}
		return false;
	}

}

