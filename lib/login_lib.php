<?php

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
	
        public static function getCurrentUrl() {
                $protocol;
                if ($_SERVER["HTTPS"] == "on")
                        $protocol = 'https';
                else
                        $protocol = 'http';
                $host = $_SERVER['HTTP_HOST'];
                $requestUri = $_SERVER['REQUEST_URI'];
                return $protocol . '://' . $host . $requestUri;
        }
	
	public static function printPage($title, $body) {
		echo '<!DOCTYPE html><html><head><meta charset="utf-8" /><title>' . $title . '</title></head><body>' . $body . '</body></html>';
	}

	public static function printAutoLoginPage($user) {
		$title = 'Forwarding to ownCloud login page';
		$body = '<form id="auto_login_form" action="' . \OC::$WEBROOT . '/index.php" method="post" enctype="application/x-www-form-urlencoded" target="_self" ><input type="hidden" id="user" name="user" value="' . $user . '"/><input type="hidden" id="password" name="password" value="irrelevant"/><noscript><input type="submit" name="login" value="Log in" /></noscript></form><script type="text/javascript" >document.getElementById("auto_login_form").submit();</script>';
		self::printPage($title, $body);	
	}
		
	public static function constructHomeDir($loginName) {		
		return \OC::$SERVERROOT . '/data/' . self::SHIB_USER_HOME_FOLDER_NAME
			. '/' . $loginName;
	}
	
	public static function persistentId2LoginName($persistentId) {
		$salt = \OCP\Config::getAppValue('user_shibboleth', 'salt', '');
                return hash('sha256', $persistentId . $salt);
	}
}

