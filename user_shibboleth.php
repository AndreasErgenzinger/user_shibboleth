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

class UserShibboleth extends \OC_User_Backend {

	public function getSupportedActions() {
		return OC_USER_BACKEND_CHECK_PASSWORD |
			OC_USER_BACKEND_GET_HOME |
			OC_USER_BACKEND_GET_DISPLAYNAME;
	}
	
	/**
	 * @brief Check if the password is correct
	 * @param $uid The username
	 * @param $password The password
	 * @returns true/false
	 *
	 * Check if the password is correct without logging in the user
	 */
	public function checkPassword($uid, $password) {

		if (Auth::getShibIdentityProvider() && $password === 'irrelevant') {

			//distinguish between internal and external Shibboleth users
			//internal users log in with their LDAP (entry)uuid,
			if (LdapBackendAdapter::uuidExists($uid)) {
				return $uid;
			}
			//external users log in with their hashed persistentID
			$persistentId = Auth::getPersistentId();
			$loginName = LoginLib::persistentId2LoginName($persistentId);
			if ($loginName === $uid) {
				$this->updateQuota($uid);
				return $uid;
			}
		}
		return false;
	}

	/**
	 * @brief Get a list of all users
	 * @returns array with all uids
	 *
	 * Get a list of all users.
	 */
	public function getUsers($search = '', $limit = 10, $offset = 0) {
		$length = strlen($search);
		if ($length === 0 || $length > 3) {
			return DB::getLoginNames($search, $limit, $offset);
		}
		return array();
	}

	/**
	 * @brief check if a user exists
	 * @param string $uid the username
	 * @return boolean
	 */
	public function userExists($uid) {
		//block the shibboleth users' home directory
		if (LoginLib::SHIB_USER_HOME_FOLDER_NAME === $uid) {
			return true;
		}
		return DB::loginNameExists($uid); 
		//all other cases are handled by the LDAP app's userExists() method
	}
	
	/**
	 * @brief get the user's home directory
	 * @param string $uid the username
	 * @return boolean
	 */
	public function getHome($uid) {
		return DB::getHomeDir($uid);
	}
	
	public function getDisplayName($uid) {
		return DB::getDisplayName($uid);
	}
	
	public function getDisplayNames($search = '', $limit = null, $offset = null) {
		return DB::getDisplayNames($search, $limit, $offset);
	}
	
	/**
	 * @brief update a user's quota
	 * @param uid the login name of an external Shibboleth user 
	 */
	private static function updateQuota($uid) {
		$quota = \OCP\Config::getAppValue('user_shibboleth', 'external_user_quota', '');
		if ($quota !== '') {
			\OCP\Config::setUserValue($uid, 'files', 'quota', \OCP\Util::computerFileSize($quota));
		}
	}
		
}
