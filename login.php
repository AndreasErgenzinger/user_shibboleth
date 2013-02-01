<?php
require_once '../../lib/base.php';
require_once 'lib/forwarding.php';
#require_once '../user_ldap/user_ldap.php';

$location = \OC::$WEBROOT;

//see if user is authenticated via shibboleth
$remoteUser = \OCA\user_shibboleth\Auth::getRemoteUser();
if ($remoteUser) {
	
	//make sure that homedir property is set ...
	$homeLdapSuffix = '@uni-konstanz.de';
	if (\OCA\user_shibboleth\Utils::endsWith($remoteUser, $homeLdapSuffix)) {
		//...  for users that are matched to an LDAP account
		$homedir = \OCP\Config::getUserValue($remoteUser, 'user_ldap', 'homedir', false);
		if ($homedir === false) {
			$connector = new \OCA\user_ldap\lib\Connection('user_ldap');
			$ldap = new \OCA\user_ldap\USER_LDAP();
			$ldap->setConnector($connector);
			$ldap->getHome($remoteUser);//writes calculated homedir value to oc_preferences
		}
	} else {
		//... for Shibboleth-authenticated visitors
		$homedir = \OCP\Config::getUserValue($remoteUser, 'user_shibboleth', 'homedir', false);
		if ($homedir === false) {
			//construct home directory path
			//TODO base on persistentID instead
			$homedir = \OCP\Config::getSystemValue("datadirectory", \OC::$SERVERROOT."/data") . '/' . $remoteUser;
			//write to oc_preferences
			\OCP\Config::setUserValue($remoteUser, 'user_shibboleth', 'homedir', $homedir);
		}
	}
	//perform OC login
	\OC_User::login($remoteUser, 'irrelevant');
} else {
	//follow shibboleth authentication procedure
	$sessionsHandlerUrl = \OCP\Config::getAppValue('user_shibboleth', 'sessions_handler_url', 0);
	$sessionInitiatorLocation = \OCP\Config::getAppValue('user_shibboleth', 'session_initiator_location', 0);
	if ($sessionsHandlerUrl !== 0 && $sessionInitiatorLocation !== 0) {
		$location = $sessionsHandlerUrl . $sessionInitiatorLocation . '?target=' . getCurrentUrl();
	}
}
header('Location: ' .  $location);
?> 
