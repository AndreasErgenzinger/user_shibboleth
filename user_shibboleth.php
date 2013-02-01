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

	/**
	 * @brief Check if the password is correct
	 * @param $uid The username
	 * @param $password The password
	 * @returns true/false
	 *
	 * Check if the password is correct without logging in the user
	 */
	public function checkPassword($uid, $password) {
		if (Auth::isAuthenticated($uid) && $password === 'irrelevant')
			return $uid;
		return false;
	}

	/**
	 * @brief Get a list of all users
	 * @returns array with all uids
	 *
	 * Get a list of all users.
	 */
	public function getUsers($search = '', $limit = 10, $offset = 0) {
		if (strlen($search) > 4)
			return DB::getUsers($search, $limit, $offset);
		else
			return array();
	}

	/**
	 * @brief check if a user exists
	 * @param string $uid the username
	 * @return boolean
	 */
	public function userExists($uid) {
		$exists = Auth::isAuthenticated($uid);
		if (!$exists) {
			$exists = DB::userExists($uid);
#			\OCP\Util::writeLog('user_shibboleth', $exists, 4);
		}
		return $exists;
		//all other cases are handled by the LDAP app's userExists() method
	}
	
	/**
	 * @brief get the user's home directory
	 * @param string $uid the username
	 * @return boolean
	 */
	public function getHome($uid) {
#		\OCP\Util::writeLog('user_shibboleth', 'getHome', 4);

		if($this->userExists($uid)) {
			$homedir = \OCP\Config::getUserValue($uid, 'user_shibboleth', 'homedir', false);
			return $homedir;
		}
		return false;
	}
}
