<?php
#require_once 'lib/login_lib.php';
require_once '../../lib/base.php';
#\OCA\user_shibboleth\Auth::isAuthenticated();

\OCA\user_shibboleth\LoginLib::printAutoLoginPage('test');
