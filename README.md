user_shibboleth
===============

ownCloud authentication app relying on the Shibboleth Service Provider

This is work in progress and relies on an appropriately configured LDAP user backend.

The following line must be added to the .htaccess file in ownCloud's base directory:

RewriteRule ^apps/user_shibboleth/login.php - [L]

The rewrite rule must be put before "RewriteRule ^apps/([^/]*)/(.*\.(css|php))$ index.php?app=$1&getfile=$2 [QSA,L]", which usually prevents direct access to php pages in the /owncloud location.
