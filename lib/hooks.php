<?php

namespace OCA\user_shibboleth;

require_once 'base.php';

class UserShibbolethHooks {

	static public function logout($parameters) {
		if (Auth::isAuthenticated(\OCP\User::getUser())) {
			unset($_SERVER['HTTP_SHIB_IDENTITY_PROVIDER']);
			unset($_SERVER['REMOTE_USER']);
		}
                return true;
	}		
}
