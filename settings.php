<?php
/**
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

$params = array('sessions_handler_url', 'session_initiator_location', 'salt', 'ldap_mail_suffix');

if($_POST) {
	foreach($params as $param) {
		if (isset($_POST[$param])) {
			OCP\Config::setAppValue('user_shibboleth', $param, $_POST[$param]);
		}
	}
}

// fill template
$tmpl = new OCP\Template( 'user_shibboleth', 'settings');
foreach ($params as $param) {
	$value = htmlentities(OCP\Config::getAppValue('user_shibboleth', $param,''));
	$tmpl->assign($param, $value);
}

// settings with default values
$tmpl->assign( 'sessions_handler_url', OCP\Config::getAppValue('user_shibboleth', 'sessions_handler_url', ''));
$tmpl->assign( 'session_initiator_location', OCP\Config::getAppValue('user_shibboleth', 'session_initiator_location', ''));
$tmpl->assign( 'salt', OCP\Config::getAppValue('user_shibboleth', 'salt', ''));
$tmpl->assign( 'ldap_mail_suffix', OCP\Config::getAppValue('user_shibboleth', 'ldap_mail_suffix', ''));

return $tmpl->fetchPage();

