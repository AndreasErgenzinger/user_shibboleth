#user_shibboleth

**user_shibboleth** is an ownCloud (version 5.0) user authentication app relying on the Shibboleth Service Provider. This app is no longer maintained, so compatibility with more recent ownCloud versions is very unlikely.

At the current stage, this project is only intended to serve as a starting point for reimplementation and as an example for federated authentication in a PHP + Apache + mod_shib + Shibboleth2 environment, in general.


###Deployment

If you have downloaded the app folder as a zip file from github, rename the app folder from "user_shibboleth-master" to "user_shibboleth".

Assuming a running configuration of Apache and the Shibboleth SP, some additional changes are required in order to protect the /owncloud location via [lazy Shibboleth authentication](https://aai-demo.switch.ch/lazy/).

* Configuring Apache:

    The following _Location_ directive must be added to the _VirtualHost_ configuration for HTTPS connections. Usually this would be in _/etc/apache2/sites-available/default-ssl_.

    ```xml
    <Location /owncloud>
        AuthType shibboleth
        Require shibboleth
        RewriteRule ^apps/user_shibboleth/login.php - [L]
    </Location>
    ```
* Configuring Shibboleth:
    
    Next, add the new path in _/etc/shibboleth/shibboleth2.xml_ like this:

    ```xml
    <RequestMapper type="Native">
        <RequestMap applicationId="default">
            <Host name="example.com">
                <Path name="owncloud" authType="shibboleth" requireSession="false"/>
            </Host>
       </RequestMap>
    </RequestMapper>
    ```
    Take care to use the server's actual host name.
    
    While you have the _shibboleth2.xml_ file open, write down the values of both the _handlerURL_ attribute of the _Sessions_ node and the _Location_ attribute of the _SessionInitiator_ node, as these must be specified on the app's settings menu.

   The app requires two user attributes, _persistent-id_ and _mail_, so make sure that the nodes with those ids are uncommented in _/etc/shibboleth/attribute-map.xml_. 

The __user_shibboleth__ app itself can be installed and enabled just like any other ownCloud app.

Fill in the two transcribed values from the SP configuration file on the app settings page, save, and you are done.

###Mapping Internal Users to LDAP User Accounts

If your ownCloud installation uses the __LDAP User and Group Backend__, then chances are, that the connected LDAP server is also used for authenticating users in the SP's Shibboleth federation. A single user could then create two ownCloud user accounts, one via the LDAP backend and one via the Shibboleth backend. We refer to these kinds of users as internal. External users, on the other hand, are those users who are only retrievable from the Shibboleth backend.

To prevent internal users from creating secondary accounts, check the _Link to LDAP Backend_ checkbox. Internal users will then always use their LDAP backend account, irrespective of the way they log into the system.

The user mapping is based on the Shibboleth and LDAP mail attributes. This allows a malicious IdP to hijack mapped user accounts by authenticating users with spoofed email addresses. To prevent this and other kinds of user impersonation, it is __strongly recommended__ to check the _Enforce Domain Similarity_ checkbox. This way users whose email address domain part does not match the domain of their respective IdP are rejected.

At the moment the internal user mapping feature only works with the first configured LDAP server.

###Version History
<pre>
0.5      - mapping users to LDAP accounts 
0.5.0.1  - improved LDAP user authentication
0.5.0.2  - accomodating PHP/DBS combinations returning SQL COUNT(*) as a string value
</pre>
