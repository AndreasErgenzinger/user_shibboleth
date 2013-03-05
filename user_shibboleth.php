<?php

/**
 * ownCloud - user_shibboleth
 *
 * @author Dominik Schmidt
 * @author Artuhr Schiwon
 * @copyright 2011 Dominik Schmidt dev@dominik-schmidt.de
 * @copyright 2012 Arthur Schiwon blizzz@owncloud.com
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU AFFERO GENERAL PUBLIC LICENSE for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\user_shibboleth;

class UserShibboleth extends \OC_User_Backend {

	public function getSupportedActions() {
		return OC_USER_BACKEND_CHECK_PASSWORD |
#			OC_USER_BACKEND_GET_HOME |
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
		\OCP\Util::writeLog('user_shibboleth', 'cp: ' . $uid . ' ' . $password, 3);
		if ($uid === 'test')
			return $uid;
#		if ($uid === 'eac07e35459b8545c3a249d55409734b20b7e8a8b23fc3aa8f04a31d8eaea436' &&
#			$password === 'irrelevant') {
#			\OCP\Util::writeLog('user_shibboleth', 'cp okay ' . $uid, 4);
#			return true;
#		}

		if (Auth::isAuthenticated() && $password === 'irrelevant') {

			\OCP\Util::writeLog('user_shibboleth', 'cp: ' . $uid . ' ' . $password, 3);

			//distinguish between internal and external Shibboleth users
			//internal users log in with their email address,
			$mail = Auth::getMail();
			if ($mail === $uid)
				return $uid;//TODO
			//external users log in with their hashed and salted persistentID
			$persistentId = Auth::getPersistentId();
			$loginName = LoginLib::persistentId2LoginName($persistentId);
			if ($loginName === $uid) {
				\OCP\Util::writeLog('user_shibboleth', 'cp okay', 4);
				return $uid;//TODO
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
		\OCP\Util::writeLog('user_shibboleth', 'getUsers search: ' . $search, 4);

		$length = strlen($search);
		if ($length === 0 || $length > 3)
			return DB::getLoginNames($search, $limit, $offset);
		else
			return array();
	}

	/**
	 * @brief check if a user exists
	 * @param string $uid the username
	 * @return boolean
	 */
	public function userExists($uid) {
		if ($uid === 'test') {
#			\OCP\Util::writeLog('user_shibboleth', 'userExists test true', 3);
			return true;
		}


		//block the shibboleth users' home directory
		\OCP\Util::writeLog('user_shibboleth', 'userExists other', 3);
		if (LoginLib::SHIB_USER_HOME_FOLDER_NAME === $uid)
			return true;
		return DB::loginNameExists($uid); 
		//all other cases are handled by the LDAP app's userExists() method
	}
	
	/**
	 * @brief get the user's home directory
	 * @param string $uid the username
	 * @return boolean
	 */
	public function getHome($uid) {
		\OCP\Util::writeLog('user_shibboleth', 'getHome', 3);
		return DB::getHomeDir($uid);
	}
	
	public function getDisplayName($uid) {
		if ($uid === 'test')
			return 'testDisplay';
		\OCP\Util::writeLog('user_shibboleth', 'getDisplayName ' . $uid, 3);
		return DB::getDisplayName($uid);
	}
	
	public function getDisplayNames($search = '', $limit = null, $offset = null) {
		return DB::getDisplayNames($search, $limit, $offset);
	}
		
}
