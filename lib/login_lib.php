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

class LoginLib {
	
	const SHIB_USER_HOME_FOLDER_NAME = 'shibboleth';

	public static function endsWith($string, $suffix, $caseInsensitive = true) {
		$stringLength = strlen($string);
		$suffixLength = strlen($suffix);
		if ($suffixLength < $stringLength) {
			$comp = substr_compare($string, $suffix, $stringLength - $suffixLength, $suffixLength, $caseInsensitive);
			if ($comp === 0)
				return true;
		}
		return false;
	}
	
	public static function getForwardingPageUrl() {
		return 'https://' . $_SERVER['HTTP_HOST'] . \OC::$WEBROOT . '/apps/user_shibboleth/login.php';
	}
	
	public static function printPage($title, $body) {
		echo '<!DOCTYPE html><html><head><meta charset="utf-8" /><title>' . $title . '</title></head><body>' . $body . '</body></html>';
	}

	public static function printAutoLoginPage($user) {
		$user = \OCP\Util::sanitizeHTML($user);
		$title = 'Forwarding to ownCloud login page';
		$body = '<form id="auto_login_form" action="' . \OC::$WEBROOT . '/index.php" method="post" enctype="application/x-www-form-urlencoded" target="_self" ><input type="hidden" id="user" name="user" value="' . $user . '"/><input type="hidden" id="password" name="password" value="irrelevant"/><noscript><input type="submit" name="login" value="Log in" /></noscript></form><script type="text/javascript" >document.getElementById("auto_login_form").submit();</script>';
		self::printPage($title, $body);	
	}
		
	public static function getHomeDirPath($loginName) {		
		return \OC::$SERVERROOT . '/data/' . self::SHIB_USER_HOME_FOLDER_NAME
			. '/' . $loginName;
	}
	
	public static function persistentId2LoginName($persistentId) {
                return hash('sha256', $persistentId);
	}
	
	/**
	 * Compares the IdP's domain with the mail address domain part and
	 * returns false iff they don't match.
	 */
	public static function checkMailOrigin($idp, $mail) {
		//trim the idp URL down to the domain part:
		$startIndex = 0;
		$endIndex = strlen($idp);
		//determine of protocol prefix part
		$index = strpos($idp, '://');
		if ($index !== false) {
			$startIndex = $index + 3;
		}
		//determine non-domain suffix
		$index = strpos($idp, '/', $startIndex);
		if ($index !== false) {
			$endIndex = $index;
		}
		$idpDomain = substr($idp, $startIndex, $endIndex - $startIndex);
		//remove non-domain part of mail address:
		$index = strpos($mail, '@');
		if ($index === false) {
			return false;//mail format error
		}
		$index += 1;
		$mailDomain = substr($mail, $index, strlen($mail) - $index);
		//compare domains (strict case):
		return self::endsWith($idpDomain, $mailDomain, false);
	}
}

