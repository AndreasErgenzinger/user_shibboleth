<?php
require_once '../../lib/base.php';

$location = \OC::$WEBROOT;

//see if user is authenticated via shibboleth
if (\OCA\user_shibboleth\Auth::isAuthenticated()) {
	$persistentId = \OCA\user_shibboleth\Auth::getPersistentId();
	$mail = \OCA\user_shibboleth\Auth::getMail();

#\OCA\user_shibboleth\LoginLib::printAutoLoginPage('test');//TODO
	
	//exit if attributes weren't retrieved
	if ($persistentId === false || $mail === false) {
		$msg = 'unavailable attributes: ';
		if ($persistentId === false)
			$msg .= 'persistentID ';
		if ($mail === false)
			$msg .= 'mail';
		\OCP\Util::writeLog('user_shibboleth', $msg, \OCP\Util::ERROR);
		\OCA\user_shibboleth\LoginLib::printPage('Attributes unavailable',
		'Some attributes could not be retrieved from the identity provider.<p/><a href="' . \OC::$WEBROOT . '">Return to the login page</a>');
		exit();//stop script execution
	}
	
	//distinguish between internal (those in the LDAP) and external Shibboleth users
	$internal = false;
	$homeLdapSuffix = \OCP\Config::getAppValue('user_shibboleth', 'ldap_mail_suffix', '');
	if ($homeLdapSuffix !== '' &&
		\OCA\user_shibboleth\LoginLib::endsWith($mail, $homeLdapSuffix)) {
		//check if there already is a matching LDAP user account in OC
		//and if so make sure that the homedir value is available to the LDAP user backend
		$connector = new \OCA\user_ldap\lib\Connection('user_ldap');
                $ldap = new \OCA\user_ldap\USER_LDAP();
                $ldap->setConnector($connector);
		if ($ldap->userExists($mail)) {
			$internal = true;
			$ldap->getHome($mail);//writes calculated homedir value to oc_preferences
			//print forwarding auto-login page
			\OCA\user_shibboleth\LoginLib::printAutoLoginPage($mail);
			exit();
		}
	}

	if (!$internal) {//user is not matched to LDAP account

		//crop $mail to fit into display_name column of oc_shibboleth_user
		if (strlen($mail) > 64)
			$mail = substr($mail, 0, 64);
		//make sure that user entry exists in oc_shibboleth_user
		$loginName = \OCA\user_shibboleth\LoginLib::persistentId2LoginName($persistentId);
		$displayName = $mail;

\OCP\Util::writeLog('shib login', 'loginName:   ' . $loginName, 3);//TODO
\OCP\Util::writeLog('shib login', 'displayName: ' . $displayName, 3);//TODO

		if (\OCA\user_shibboleth\DB::loginNameExists($loginName)) {
			//update display name if it has changed since last login
			if ($displayName !== \OCA\user_shibboleth\DB::getDisplayName($loginName))
				\OCA\user_shibboleth\DB::updateDisplayName($loginName, $displayName);
		} else {
			//create a new user account
			$homeDir = \OCA\user_shibboleth\LoginLib::constructHomeDir($loginName);
			\OCA\user_shibboleth\DB::addUser($loginName, $displayName, $homeDir);
		}
		//print forwarding auto-login page

\OCP\Util::writeLog('shib login', 'lalala', 3);//TODO
		\OCA\user_shibboleth\LoginLib::printAutoLoginPage($loginName);
		exit();
	}
} else {//not authenticated, yet
	//follow shibboleth authentication procedure
	$sessionsHandlerUrl = \OCP\Config::getAppValue('user_shibboleth', 'sessions_handler_url', '');
	$sessionInitiatorLocation = \OCP\Config::getAppValue('user_shibboleth', 'session_initiator_location', '');
	if ($sessionsHandlerUrl !== '' && $sessionInitiatorLocation !== '') {
		$location = $sessionsHandlerUrl . $sessionInitiatorLocation . '?target=' . \OCA\user_shibboleth\LoginLib::getCurrentUrl();
	} //TODO maybe ELSE {print error page}
}
header('Location: ' .  $location);
?> 
