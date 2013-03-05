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

	public static function isAuthenticated() {
		if (isset($_SERVER['Shib-Identity-Provider']) &&
			$_SERVER['Shib-Identity-Provider'] !== '') {
			return true;
		}
		return false;
	}
	
	public static function getMail() {//used by login.php
                if (isset($_SERVER['mail']) && $_SERVER['mail'] !== '')
                        return $_SERVER['mail'];
                return false;
        }
	
        public static function getPersistentId() {//used by login.php
                if (isset($_SERVER['persistent-id']) && $_SERVER['persistent-id'] !== '')
                        return $_SERVER['persistent-id'];
                return false;
        }

}
