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

require_once '../../lib/base.php';

$location = \OC::$WEBROOT;

$enabled = \OCP\App::isEnabled('user_shibboleth');
$sessionsHandlerUrl = \OCP\Config::getAppValue('user_shibboleth', 'sessions_handler_url', '');
$sessionInitiatorLocation = \OCP\Config::getAppValue('user_shibboleth', 'session_initiator_location', '');
if ($enabled && $sessionsHandlerUrl !== '' && $sessionInitiatorLocation !== '') {//enabled and hopefully configured

	//see if user is authenticated via shibboleth
	$idp = \OCA\user_shibboleth\Auth::getShibIdentityProvider();
	if ($idp) {
		$persistentId = \OCA\user_shibboleth\Auth::getPersistentId();
		$mail = \OCA\user_shibboleth\Auth::getMail();
		
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
			exit();
		}

		//check for potential email address spoofing
		if ((\OCP\Config::getAppValue('user_shibboleth', 'enforce_domain_similarity', '0') === '1') && !\OCA\user_shibboleth\LoginLib::checkMailOrigin($idp, $mail)) {
			//log and print error page
			\OCP\Util::writeLog('user_shibboleth', 'domain mismatch: ' . $idp . ' ' . $mail, \OCP\Util::ERROR);
			\OCA\user_shibboleth\LoginLib::printPage('Domain Mismatch', 'The domain of your identity provider does not match the domain part of your email address. This event has been logged.');
			exit();
		}
		
		//distinguish between internal (those in the LDAP) and external Shibboleth users
		$loginName = \OCA\user_shibboleth\LdapBackendAdapter::getUuid($mail);
		if ($loginName) {//user is internal, backends are enabled, and user mapping is active
			\OCA\user_shibboleth\LdapBackendAdapter::initializeUser($loginName);
		} else {//user is external
			//crop $mail to fit into display_name column of oc_shibboleth_user
			if (strlen($mail) > 64) {
				$mail = substr($mail, 0, 64);
			}
			//make sure that user entry exists in oc_shibboleth_user
			$loginName = \OCA\user_shibboleth\LoginLib::persistentId2LoginName($persistentId);
			$displayName = $mail;

			if (\OCA\user_shibboleth\DB::loginNameExists($loginName)) {
				//update display name if it has changed since last login
				if ($displayName !== \OCA\user_shibboleth\DB::getDisplayName($loginName)) {
					\OCA\user_shibboleth\DB::updateDisplayName($loginName, $displayName);
				}
			} else {
				//create a new user account
				$homeDir = \OCA\user_shibboleth\LoginLib::getHomeDirPath($loginName);
				\OCA\user_shibboleth\DB::addUser($loginName, $displayName, $homeDir);
			}
		}
		//perform OC login
		\OC_User::login($loginName, 'irrelevant');
	} else {//not authenticated, yet
		//follow shibboleth authentication procedure
		$location = $sessionsHandlerUrl . $sessionInitiatorLocation . '?target=' . \OCA\user_shibboleth\LoginLib::getForwardingPageUrl();
	}
} else {
	\OCP\Util::writeLog('user_shibboleth', 'backend not enabled or not configured', \OCP\Util::INFO);
}
header('Location: ' .  $location);
?> 
