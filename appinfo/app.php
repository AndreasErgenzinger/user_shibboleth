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

require_once \OC_App::getAppPath('user_shibboleth') . '/appinfo/bootstrap.php';


OCP\App::registerAdmin('user_shibboleth', 'settings');

// register user backend
OC_User::useBackend(new OCA\user_shibboleth\UserShibboleth());

// add settings page to navigation
$entry = array(
	'id' => 'user_shibboleth_settings',
	'order'=>1,
	'href' => OCP\Util::linkTo( 'user_shibboleth', 'settings.php' ),
	'name' => 'Shibboleth Authentication'
);

//add javascript and css
if (!OCP\User::isLoggedIn()) {
	OCP\Util::addScript('user_shibboleth', 'shibboleth');
	OCP\Util::addStyle('user_shibboleth', 'shibboleth');
}
OCP\Util::addStyle('user_shibboleth', 'settings');

