<?php

/**
 * ownCloud - user_shibboleth
 *
 * @author Andreas Ergenzinger
 * @copyright 2012 Andreas Ergenzinger andreas.ergenzinger@uni-konstanz.de
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
 */

namespace OCA\user_shibboleth;

class Auth {
	
	/**
         * @brief get the REMOTE_USER environment variable
         * @return the REMOTE_USER environment variable or FALSE, if
	 * the variable has not been set via shibboleth authentication.
	 * Requires "ShibUseHeaders On" in apache location configuration.
         */
	public static function getRemoteUser() {
		if (isset($_SERVER['HTTP_SHIB_IDENTITY_PROVIDER']) &&
			isset($_SERVER['REMOTE_USER'])) {
			return $_SERVER['REMOTE_USER'];
		}
		return false;
	}
	
	public static function isAuthenticated($uid) {
		return self::getRemoteUser() === $uid;
	}
}
